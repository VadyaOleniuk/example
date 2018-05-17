<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\CompanyBundle\Entity\SpecificTheme;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSpecificThemeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $specific = new SpecificTheme();
        $specific->setName('Logistic');

        $manager->persist($specific);
        $manager->flush();
    }
}