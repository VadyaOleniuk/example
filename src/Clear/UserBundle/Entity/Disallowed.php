<?php

namespace Clear\UserBundle\Entity;

use JMS\Serializer\Annotation\MaxDepth;
/**
 * Disallowed
 */
class Disallowed
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $actionId;

    /**
     * @var string
     */
    private $actionName;

    /**
     * @var \Clear\UserBundle\Entity\Role
     * @MaxDepth(2)
     */
    private $roleUser;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set actionId
     *
     * @param string $actionId
     *
     * @return Disallowed
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Get actionId
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->actionId;
    }

    /**
     * Set roleUser
     *
     * @param Role $roleUser
     *
     * @return Disallowed
     */
    public function setRoleUser(Role $roleUser = null)
    {
        $this->roleUser = $roleUser;

        return $this;
    }

    /**
     * Get roleUser
     *
     * @return Role
     */
    public function getRoleUser()
    {
        return $this->roleUser;
    }

    /**
     * Set actionName
     *
     * @param string $actionName
     *
     * @return Disallowed
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;

        return $this;
    }

    /**
     * Get actionName
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }
}
