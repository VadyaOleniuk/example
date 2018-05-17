<?php

namespace Clear\OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;

/**
 * RefreshToken
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @var int
     */
    protected $id;

    protected $user;

    protected $client;
}
