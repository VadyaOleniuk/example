<?php

namespace Clear\LogBundle\Events;


/**
 * Class ContentEvent
 *
 * @package Clear\LogBundle\Events
 */
class ContentEvent extends AbstractEvent
{
    const CONTENT_ADDED   = 'content_added';
    const CONTENT_UPDATED = 'content_updated';
    const CONTENT_DELETED = 'content_deleted';
    const CONTENT_CHANGE_STATUS = 'content_change_status';
    const CONTENT_ADDED_CATEGORY = 'content_added_category';
    const CONTENT_DELETE_CATEGORY = 'content_delete_category';
    const CONTENT_ADDED_TAG = 'content_added_tag';
    const CONTENT_DELETE_TAG = 'content_delete_tag';
}