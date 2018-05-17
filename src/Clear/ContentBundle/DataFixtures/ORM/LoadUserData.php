<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const ACTIVE_USER_LOGIN = 'test@test.com';
    const USER_PASSWORD = '123';
    const REFERENCE_ACTIVE_USER = 'active-user';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        /** @var EncoderFactory $encoderFactory */
        $encoderFactory = $this->container->get('security.encoder_factory');

        $user = new User();
        $encoder = $encoderFactory->getEncoder($user);
        $salt = md5(uniqid('', true));
        $encodedPassword = $encoder->encodePassword(self::USER_PASSWORD, $salt);

        $user->setEmail('test@test.com');
        $user->setUsername(uniqid('u'));
        $user->setName('Hawk');
        $user->setFunction('function');
        $user->setJobTitle('Worker');
        $user->setLastName('Hawk');
        $user->setEnabled(true);

        $user->setSalt($salt);
        $user->setPassword($encodedPassword);

        $role = $manager->getRepository('ClearUserBundle:Role')->find(1);
        $user->addRoleUser($role);

        $manager->persist($user);

        $this->addReference(self::REFERENCE_ACTIVE_USER, $user);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}