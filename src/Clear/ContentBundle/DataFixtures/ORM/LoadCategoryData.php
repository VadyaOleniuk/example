<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\ContentBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $food = new Category();
        $food->setTitle('Food');
        $food->setIsActive(0);
        $food->setDescription('Category Category Category');


        $fruits = new Category();
        $fruits->setTitle('Fruits');
        $fruits->setParent($food);
        $fruits->setIsActive(0);
        $fruits->setDescription('Category Category Category');


        $vegetables = new Category();
        $vegetables->setTitle('Vegetables');
        $vegetables->setParent($food);
        $vegetables->setIsActive(0);
        $vegetables->setDescription('Category Category Category');


        $carrots = new Category();
        $carrots->setTitle('Carrots');
        $carrots->setParent($vegetables);
        $carrots->setIsActive(0);
        $carrots->setDescription('Category Category Category');


        $manager->persist($food);
        $manager->persist($fruits);
        $manager->persist($vegetables);
        $manager->persist($carrots);
        $manager->flush();
    }
}


