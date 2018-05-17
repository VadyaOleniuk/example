<?php

namespace Clear\SecurityBundle\Security;


use Doctrine\ORM\EntityManager;

class Securable
{
    protected  $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function hasAccess($actionId, $roles)
    {

        if (is_string($roles)) {

            return true;
        }

        foreach ($roles as $role) {
            $actions = $this->manager->getRepository('ClearUserBundle:Disallowed')->findBy(['actionId' => $actionId]);
            foreach ($actions as $action) {
                if ($action->getActionId() ==  $actionId && $action->getRoleUser()->getName() == $role) {

                    return false;
                }
            }
        }

        return true;
    }
}