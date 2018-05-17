<?php

namespace Clear\UserBundle\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Initializes the locale based on the current request.
 *
 */
class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * Constructor.
     *
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    public function onKernelController()
    {
/*        $token = $this->container->get('security.token_storage')->getToken();
        if (!empty($token->getCredentials())) {
            $user = $token->getUser();
            $user->setLastLogin(new \DateTime());

            $this->manager->persist($user);
            $this->manager->flush();
        }*/
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array(array('onKernelController', 0))
        );
    }
}