<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\ContentBundle\Entity\Category;
use Clear\ContentBundle\Entity\Content;
use Clear\ContentBundle\Entity\ContentType;
use Clear\ContentBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setTitle('Category');
        $category->setIsActive(0);
        $category->setDescription('Category Category Category');

        $manager->persist($category);

        $contentType = new ContentType();
        $contentType->setIcon('Icon');
        $contentType->setType('text');
        $contentType->setDescription('description');
        $contentType->setIsActive(1);

        $contentType->setForm(["test" => "test"]);
        $manager->persist($contentType);

        $tag = new Tag();
        $tag->setName('Second');
        $tag->setDescription('Second Second Second');
        $tag->setIsActive(1);

        $manager->persist($tag);

        $content = new Content();
        $content->setTitle('Title');
        $content->setDescription('Description');
        $content->setStatus(1);
        $content->setContent('Content');

        $content->setPublishedAt(new \DateTime("now"));
        $content->setImageName('picter.png');
        $content->setTypeValues(['test' => 'test']);

        $content->addTag($tag);
        $content->addCategory($category);
        $content->setContentType($contentType);

        $manager->persist($content);
        $manager->flush();
    }
}