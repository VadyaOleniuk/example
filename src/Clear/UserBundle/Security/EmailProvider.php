<?php
namespace Clear\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider;

class EmailProvider extends UserProvider
{
    public function loadUserByUsername($username)
    {
        return $this->userManager->findUserByEmail($username);
    }
}