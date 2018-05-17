<?php

namespace Clear\OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;

/**
 * AuthCode
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var int
     */
    protected $id;

    protected $user;

    protected $client;
}
