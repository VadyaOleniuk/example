<?php
namespace Clear\ContentBundle\EventListener;

use Clear\ContentBundle\Entity\Content;
use Clear\ContentBundle\Model\ContentList;
use Clear\FileStorageBundle\Entity\FileStorage;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class SerializationListener implements EventSubscriberInterface
{
    const PUBLISHED = 1;
    private $helper;
    private $em;
    private $user;

    public function __construct($helper, $event_manager, $token)
    {
        $this->helper = $helper;
        $this->em = $event_manager;
        $this->user = $token->getToken()->getUser();
    }

    public static function getSubscribedEvents()
    {
        return array(
            [
                'event' => 'serializer.pre_serialize',
                'class' => Content::class,
                'method' => 'onPreSerialize',

            ],
            [
                'event' => 'serializer.post_serialize',
                'class' => Content::class,
                'method' => 'onPostSerialize',

            ],
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        if($event->getObject() instanceof Content) {
            $types = $event->getObject()->getTypeValues();

            $img = $this->helper->asset($event->getObject(), 'imageFile');
            $visitor = $event->getVisitor();
            $visitor->addData('image_path', $img);
            $visitor->addData('shortContent', $this->getShortContent($event->getObject()));
            if(!empty($types)) {
                $formResource = [];
                foreach ($types as $key => $type) {
                    $formResource[$key] = $type;
                    if (!empty($type['file'])) {
                        $file = $this->em->getRepository(FileStorage::class)->findOneBy(['fileName' => $type['file']]);
                        $formResource[$key] = array_merge($formResource[$key], $this->getParams($file));
                    }
                }
                $visitor->addData('formResource', $formResource);
            }
        }
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        if($event->getObject() instanceof Content && $this->user->isRole("User")) {
            foreach ($event->getObject()->getChildren() as $child) {
                if($child->getStatus() !== self::PUBLISHED) {
                    $event->getObject()->removeChildren($child);
                }else{
                    if($companies = $child->getCompanies()){
                        $noContent =true;
                        foreach ($companies as $company){
                            $event->getObject()->removeChildren($child);
                            if($this->user->getCompany()->getId() === $company->getId()){
                                $noContent = false;
                            }
                        }
                        if(empty($noContent)){
                            $event->getObject()->addChildren($child);
                        }
                    }
                }
            }
        }
    }
    private function getParams($file)
    {
        return [
            'path' => $this->helper->asset($file, 'file'),
            'id' => $file->getId(),
            'alt' => $file->getAlt(),
            'name' => $file->getOriginalName(),
        ];
    }

    private function getShortContent( $object )
    {
        if($object->getContent()){
            $content = strip_tags(ContentList::getContentList($object->getContent()));
            return substr($content,0,200);
        }
        return null;

    }

    private function closetags($html) {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}