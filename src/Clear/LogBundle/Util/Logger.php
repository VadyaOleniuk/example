<?php
namespace Clear\LogBundle\Util;

use Clear\LogBundle\Events\ContentEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Clear\ContentBundle\Entity\Content;
use Doctrine\ORM\EntityRepository;

class Logger
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractSubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Content $content
     * @param $arrayBefore
     * @param $arrayAfter
     */
    public function logContent(Content $content, $arrayBefore, $arrayAfter)
    {
        $arrayAfter = json_decode(json_encode($arrayAfter), true);
        $arrayBefore = json_decode(json_encode($arrayBefore), true);

        $results = $this->arrayDiffAssocRecursive($arrayAfter[0], $arrayBefore[0]);



        if (isset($results['status'])) {
            $this->container->get('event_dispatcher')->dispatch(
                ContentEvent::CONTENT_CHANGE_STATUS,
                new ContentEvent($content)
            );
        }

        // Added categories
        if (isset($results['categories'])) {
            $this->container->get('event_dispatcher')->dispatch(
                ContentEvent::CONTENT_ADDED_CATEGORY,
                new ContentEvent($content)
            );
        }

        // Added Tags
        if (isset($results['tags'])) {
            $this->container->get('event_dispatcher')->dispatch(
                ContentEvent::CONTENT_ADDED_TAG,
                new ContentEvent($content)
            );
        }

        $reverse = $this->arrayDiffAssocRecursive($arrayBefore[0], $arrayAfter[0]);
        // Deleted categories
        if (isset($reverse['categories'])) {
            $this->container->get('event_dispatcher')->dispatch(
                ContentEvent::CONTENT_DELETE_CATEGORY,
                new ContentEvent($content)
            );
        }

        // Deleted Tags
        if (isset($reverse['tags'])) {
            $this->container->get('event_dispatcher')->dispatch(
                ContentEvent::CONTENT_DELETE_TAG,
                new ContentEvent($content)
            );
        }

        // Update content
        $this->container->get('event_dispatcher')->dispatch(
            ContentEvent::CONTENT_UPDATED,
            new ContentEvent($content)
        );
    }

    /**
     * @param $array1
     * @param $array2
     * @return bool
     */
    public function arrayDiffAssocRecursive($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffAssocRecursive($value, $array2[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($array2[$key]) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? false : $difference;
    }
}