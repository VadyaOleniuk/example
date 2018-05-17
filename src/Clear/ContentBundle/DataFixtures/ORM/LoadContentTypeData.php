<?php

namespace ClearContentBundle\DataFixtures\ORM;


use Clear\ContentBundle\Entity\ContentType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadContentTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contentType = new ContentType();
        $contentType->setType('video');
        $contentType->setIcon('Fugiat quidem soluta');

        $contentType->setDescription('Saepe aperiam qui possimus');
        $contentType->setIsActive(1);
        $contentType->setForm(
            [
            "comment" => [
                "title" => "Article video",
                "name" => "Video {number}: Url and transcript"
            ],
            "form" => [
                "link" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\UrlType",
                    "option" => "url",
                    "title" => "Video URL",
                    "placeholder" => "Insert vide link here"
                ],
                "textarea" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
                    "option" => "textarea",
                    "title" => "Transcript for video",
                    "placeholder" => "Transcript for video"
                ]
            ],
            "type" => "video"
            ]
        );
        $manager->persist($contentType);

        $contentType = new ContentType();
        $contentType->setType('template');
        $contentType->setIcon('Fugiat quidem soluta');

        $contentType->setDescription('Saepe aperiam qui possimus');
        $contentType->setIsActive(1);
        $contentType->setForm(
            [
            "comment" => [
                "title" => "Article template",
                "name" => "Template {number}: Url and transcript"
            ],
            "form" => [
                "link" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\UrlType",
                    "option" => "url",
                    "title" => "Transcript for video",
                    "placeholder" => "Transcript for video"
                ],
                "textarea" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
                    "option" => "textarea",
                    "title" => "Transcript for resource",
                    "placeholder" => "Transcript for resource"
                ],
                "file" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType",
                "option" => "file",
                    "title" => "Upload resource",
                    "placeholder" => "Upload resource"
                ]
            ],
            "type" => "resource"
            ]
        );
        $manager->persist($contentType);

        $contentType = new ContentType();
        $contentType->setType('document');
        $contentType->setIcon('Fugiat quidem soluta');
        $contentType->setDescription('Saepe aperiam qui possimus');
        $contentType->setIsActive(1);
        $contentType->setForm(
            [
            "comment" => [
                "title" => "Article document",
                "name" => "Document {number}: Url and transcript"
            ],
            "form" => [
                "link" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\UrlType",
                    "option" => "url",
                    "title" => "Insert resource URL",
                    "placeholder" => "Insert resource link here"
                ],
                "textarea" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType",
                    "option" => "textarea",
                    "title" => "Transcript for resource",
                    "placeholder" =>"Transcript for resource"
                ],
                "file" => [
                    "type" => "Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType",
                    "option" => "file",
                    "title" => "Upload resource",
                    "placeholder" => "Upload resource"
                ]
            ],
            "type" => "resource"
            ]
        );
        $manager->persist($contentType);

        $contentType = new ContentType();
        $contentType->setType('Screen Text');
        $contentType->setIcon('Fugiat quidem soluta');

        $contentType->setDescription('Saepe aperiam qui possimus');
        $contentType->setIsActive(1);
        $contentType->setForm('');
        $manager->persist($contentType);

        $contentType = new ContentType();
        $contentType->setType('Case Study');
        $contentType->setIcon('Fugiat quidem soluta');

        $contentType->setDescription('Saepe aperiam qui possimus');
        $contentType->setIsActive(1);
        $contentType->setForm('');
        $manager->persist($contentType);

        $manager->flush();
    }
}