<?php

namespace ClearOAuthBundle\DataFixtures\ORM;

use Clear\OAuthBundle\Entity\RefreshToken;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRefreshTokenData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface, FixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $refreshToken = new RefreshToken();

        $refreshToken->setExpiresAt($this->container->getParameter('test_parameters')['expires']);
        $refreshToken->setToken($this->container->getParameter('test_parameters')['token']);

        $client = $manager->getRepository('ClearOAuthBundle:Client')->find(1);
        $user = $manager->getRepository('ClearUserBundle:User')->find(1);
        $refreshToken->setClient($client);
        $refreshToken->setUser($user);

        $manager->persist($refreshToken);
        $manager->flush();
    }

    public function getOrder()
    {
        return 80;
    }
}