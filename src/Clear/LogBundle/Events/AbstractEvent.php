<?php
namespace Clear\LogBundle\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractEvent
 *
 * @package Clear\LogBundle\Events
 */
abstract class AbstractEvent extends Event
{
    /**
     * @var null
     */
    protected $entity;

    /**
     * AbstractEvent constructor.
     *
     * @param null $entity
     */
    public function __construct($entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * @return bool|null
     */
    public function getEntity()
    {
        if($this->entity != null) {
            return $this->entity;
        }
        return false;
    }
}