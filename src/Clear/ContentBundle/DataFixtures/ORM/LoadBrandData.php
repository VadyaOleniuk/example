<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\CompanyBundle\Entity\Brand;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBrandData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $brand = new Brand();
        $brand->setName('Name');
        $brand->setFields(['test' => 'test', 'test2' => 'test2']);

        $manager->persist($brand);
        $manager->flush();
    }
}