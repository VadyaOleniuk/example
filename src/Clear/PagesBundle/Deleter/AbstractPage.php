<?php

namespace Clear\PagesBundle\Deleter;

use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractPage
{
    /**
     * @var EntityManagerInterface
     */
    public $manager;

    /**
     * @var ContainerInterface
     */
    public $container;


    /**
     * @param EntityManagerInterface $manager
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $manager, ContainerInterface $container)
    {
        $this->manager = $manager;
        $this->container = $container;
    }

    abstract public function getFileNames($content);

    abstract  public function getFileNamesForDelete($oldNames, $newNames);

    abstract public function deletePageFiles($needDelete);

    public function getNeedDelete($keys, $oldNames, $newNames)
    {
        $needDelete = [];
        foreach ($keys as $key) {
            $needDelete[$key] = array_diff ($oldNames[$key], $newNames[$key]);
        }

        return $needDelete;
    }

    public function deleteFiles($files)
    {
        foreach ($files as $fileName) {
            $fs = new Filesystem();

            $file = $this->manager->getRepository('ClearFileStorageBundle:FileStorage')->findOneBy(['fileName' => $fileName]);
            if (null !== $file) {
                $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
                $fs->remove(array('file', $helper->asset($file, 'file'), $file->getFileName()));
                $this->manager->remove($file);
                $this->manager->flush();
            }
        }
    }

}