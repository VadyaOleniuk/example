<?php

namespace Clear\OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;

/**
 * AccessToken
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var int
     */
    protected $id;

    protected $user;

    protected $client;
}
