<?php

namespace Clear\OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Client
 */
class Client extends BaseClient
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;


    public function __construct()
    {
        parent::__construct();
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
     * @return Client
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
}
