<?php

namespace Clear\LogBundle\EventSubscriber;

use Clear\LogBundle\Events\ContentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContentSubscriber
 *
 * @package Clear\LogBundle\EventSubscriber
 */
class ContentSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContentEvent::CONTENT_ADDED    => 'onContentAdded',
            ContentEvent::CONTENT_UPDATED  => 'onContentUpdated',
            ContentEvent::CONTENT_DELETED  => 'onContentDeleted',
            ContentEvent::CONTENT_CHANGE_STATUS => 'onChangeStatus',
            ContentEvent::CONTENT_ADDED_CATEGORY => 'onAddedCategory',
            ContentEvent::CONTENT_DELETE_CATEGORY => 'onDeletedCategory',
            ContentEvent::CONTENT_ADDED_TAG => 'onAddedTag',
            ContentEvent::CONTENT_DELETE_TAG => 'onDeletedTag'
        ];
    }

    /**
     * @param ContentEvent $event
     */
    public function onContentAdded(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_ADDED, [
            'content' => $event->getEntity(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onContentUpdated(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_UPDATED, [
            'content' => $event->getEntity(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onContentDeleted(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_DELETED, [
            'content' => $event->getEntity(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onChangeStatus(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_CHANGE_STATUS, [
            'content' => $event->getEntity(),
            'status' => $event->getEntity()->getStatus(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onAddedCategory(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_ADDED_CATEGORY, [
            'content' => $event->getEntity(),
            'caterories' => $event->getEntity()->getCategories(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onDeletedCategory(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_DELETE_CATEGORY, [
            'content' => $event->getEntity(),
            'caterories' => $event->getEntity()->getCategories(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onAddedTag(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_ADDED_TAG, [
            'content' => $event->getEntity(),
            'tags' => $event->getEntity()->getTags(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }

    /**
     * @param ContentEvent $event
     */
    public function onDeletedTag(ContentEvent $event)
    {
        $this->logEntity(
            ContentEvent::CONTENT_DELETE_TAG, [
            'content' => $event->getEntity(),
            'tags' => $event->getEntity()->getTags(),
            'user' => $event->getEntity()->getUser()
            ]
        );
    }
}