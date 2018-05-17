<?php
namespace Clear\ContentBundle\Util;

use Clear\UserBundle\Entity\User;

class Util
{
    /**
     * @param User $user
     * @return array
     */
    public function getRoles(User $user) 
    {
        $roles = $user->getRoleUsers();

        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray[] = $role->getName();
        }

        return $rolesArray;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAdmin(User $user)
    {
        $roles = $user->getRoleUsers();
        foreach ($roles as $role) {
            if ($role->getName() == 'Admin') {

                return true;
            }
        }

        return false;
    }
}