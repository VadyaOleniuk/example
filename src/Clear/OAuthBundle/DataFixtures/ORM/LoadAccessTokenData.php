<?php

namespace ClearOAuthBundle\DataFixtures\ORM;


use Clear\OAuthBundle\Entity\AccessToken;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAccessTokenData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface, FixtureInterface
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

        $accessToken = new AccessToken();
        $accessToken->setExpiresAt($this->container->getParameter('test_parameters')['expires']);
        $accessToken->setToken($this->container->getParameter('test_parameters')['access_token']);

        $client = $manager->getRepository('ClearOAuthBundle:Client')->find(1);
        $user = $manager->getRepository('ClearUserBundle:User')->find(1);
        $accessToken->setClient($client);
        $accessToken->setUser($user);

        $manager->persist($accessToken);
        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }
}