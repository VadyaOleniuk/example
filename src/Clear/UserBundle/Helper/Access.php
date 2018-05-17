<?php

namespace Clear\UserBundle\Helper;


use Clear\ContentBundle\Entity\Content;
use Clear\UserBundle\Entity\User;

class Access
{
    public function hasAccess($user, $content)
    {
        /** @var  $user User */
        $userRoles = $user->getRoleUsers();

        $userRolesArray = [];
        foreach ($userRoles as $userRole) {
            $userRolesArray[] = $userRole->getName();
        }
        /** @var  $content Content */
        $contentRoles = $content->getRoles();

        $contentRoleArray = [];
        foreach ($contentRoles as $contentRole) {
            $contentRoleArray[] = $contentRole->getName();
        }

        if (empty(array_intersect($contentRoleArray, $userRolesArray))) {

            return false;
        }

        return true;
    }
}
