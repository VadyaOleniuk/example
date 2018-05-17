<?php

namespace Clear\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Clear\UserBundle\Entity\User;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
/**
 * Role
 */
class Role
{
    /**
     * @var int
     * @Groups({"details"})
     */
    private $id;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $contents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->contents = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Role
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $contents
     * @return Role
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
        return $this;
    }
}
