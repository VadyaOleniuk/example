<?php

namespace Clear\SecurityBundle\Annotations\Driver;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Clear\SecurityBundle\Security\Securable as SecurableUser;
use Clear\SecurityBundle\Annotations;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;


class AnnotationDriver
{

    private $reader;

    private $tokenStorage;

    private $manager;

    public function __construct(Reader $reader, TokenStorage $tokenStorage, EntityManager $manager)
    {
        $this->reader = $reader;
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
    }


    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);
        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
            if(isset($configuration->actionId) && isset($configuration->actionName)) {

                $permission = new SecurableUser($this->manager);
                if (is_string($this->tokenStorage->getToken()->getUser())) {
                    $roles = 'anon.';
                } else {
                    $roles = [];
                    foreach ($this->tokenStorage->getToken()->getUser()->getRoleUsers() as $role) {
                        $roles[] = $role->getName();
                    }
                }

                if(!$permission->hasAccess($configuration->actionId, $roles)) {
                    throw new AccessDeniedHttpException();
                }
            }
        }
    }
}