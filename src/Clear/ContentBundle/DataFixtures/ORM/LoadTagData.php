<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\ContentBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTagData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('First');
        $tag->setDescription('First First First');
        $tag->setIsActive(1);

        $manager->persist($tag);
        $manager->flush();
    }
}