<?php
namespace Clear\PagesBundle\EventListener;

use Clear\ContentBundle\Entity\Content;
use Clear\FileStorageBundle\Entity\FileStorage;
use Clear\PagesBundle\Entity\Pages;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class SerializationListener implements EventSubscriberInterface
{
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
                'event' => 'serializer.post_serialize',
                'class' => Pages::class,
                'method' => 'onPostSerialize',

            ],
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        if($event->getObject() instanceof Pages){
            $content = $event->getObject()->getContent();

            if(!empty($content)) {
                $formResource = [];
                switch ($event->getObject()->getType()){
                    case 'ask':
                        $formResource = $this->getAskPage($content);
                        break;
                    case 'homepage':
                        $formResource = $this->getHomePage($content);
                        break;
                }
                $visitor = $event->getVisitor();
                $visitor->addData('formResource', $formResource);
            }
        }
    }

    private function getHomePage($content)
    {
        if(!empty($content['image'])){
            $content = array_merge($content,$this->getFileParams($content['image']));
        }
        if(!empty($content['articles'])){

            foreach ($content['articles'] as $key => $type) {
                if (!empty($type['imgNormal'])) {
                    $content['articles'][$key]['imgNormalParams'] = $this->getFileParams($type['imgNormal']);
                }
                if (!empty($type['imgContrast'])) {
                    $content['articles'][$key]['imgContrastParams'] = $this->getFileParams($type['imgContrast']);
                }
            }
        }
        return $content;
    }

    private function getFileParams($name)
    {
        $file = $this->em->getRepository(FileStorage::class)->findOneBy(['fileName' => $name]);
        $result['path'] = $this->helper->asset($file, 'file');
        $result['id'] =  $file->getId();
        $result['alt'] =  $file->getAlt();
        return $result;
    }

    private function getAskPage($content)
    {

        if(!empty($content['image'])){
            $content = array_merge($content,$this->getFileParams($content['image']));
        }
        if(!empty($content['list'])){

            foreach ($content['list'] as $key => $type) {
                if (!empty($type['img'])) {
                    $content['list'][$key] = $this->getFileParams($type['img']);
                }

            }
        }
        return $content;
    }
}