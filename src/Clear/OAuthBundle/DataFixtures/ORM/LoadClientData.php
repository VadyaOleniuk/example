<?php

namespace ClearOAuthBundle\DataFixtures\ORM;


use Clear\OAuthBundle\Entity\Client;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadClientData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface, FixtureInterface
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
        $client = new Client();
        $client->setName('client1');
        $client->setAllowedGrantTypes(['password']);
        $client->setRandomId($this->container->getParameter('test_parameters')['random_id']);
        $client->setRedirectUris(['www.client1.com']);
        $client->setSecret($this->container->getParameter('test_parameters')['secret']);
        $manager->persist($client);

        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}