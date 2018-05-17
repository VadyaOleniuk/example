<?php

namespace Clear\LogBundle\Entity;

use Clear\UserBundle\Entity\User;
use Clear\ContentBundle\Entity\Content;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Log
 */
class Log
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $action;

    /**
     * @var int
     */
    private $level;

    /**
     * @var string
     */
    private $levelName;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var User
     * @MaxDepth(1)
     */
    private $user;

    /**
     * @var Content
     * @MaxDepth(1)
     */
    private $content;

    /**
     * @var integer
     */
    private $userIdInt;

    /**
     * @var integer
     */
    private $contentIdInt;

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
     * Set message
     *
     * @param string $message
     *
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return Log
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Log
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set levelName
     *
     * @param string $levelName
     *
     * @return Log
     */
    public function setLevelName($levelName)
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * Get levelName
     *
     * @return string
     */
    public function getLevelName()
    {
        return $this->levelName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Log
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Log
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    public function createLog()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set content
     *
     * @param Content $content
     *
     * @return Log
     */
    public function setContent(Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set userIdInt
     *
     * @param integer $userIdInt
     *
     * @return Log
     */
    public function setUserIdInt($userIdInt)
    {
        $this->userIdInt = $userIdInt;

        return $this;
    }

    /**
     * Get userIdInt
     *
     * @return integer
     */
    public function getUserIdInt()
    {
        return $this->userIdInt;
    }

    /**
     * Set contentIdInt
     *
     * @param integer $contentIdInt
     *
     * @return Log
     */
    public function setContentIdInt($contentIdInt)
    {
        $this->contentIdInt = $contentIdInt;

        return $this;
    }

    /**
     * Get contentIdInt
     *
     * @return integer
     */
    public function getContentIdInt()
    {
        return $this->contentIdInt;
    }
}
