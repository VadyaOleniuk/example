<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\UserBundle\Entity\Role;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roles = ['User', 'Partner_user', 'Assured_user', 'Client_admin', 'Admin'];
        foreach ($roles as $roleUser) {
            $role = new Role();
            $role->setName($roleUser);
            $manager->persist($role);
        }

        $manager->flush();
    }
}