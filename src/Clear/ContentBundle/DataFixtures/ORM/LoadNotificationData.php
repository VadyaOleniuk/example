<?php

namespace ClearContentBundle\DataFixtures\ORM;


use Clear\NotificationBundle\Entity\Notification;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadNotificationData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $notification = new Notification();
        $notification->setTitle('Title');
        $notification->setEmail('test@gmail.com');
        $notification->setMessage('Message for all');
        $notification->setType('Message');

        $manager->persist($notification);
        $manager->flush();
    }
}