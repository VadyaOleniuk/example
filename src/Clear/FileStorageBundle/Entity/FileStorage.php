<?php

namespace Clear\FileStorageBundle\Entity;

use Clear\ContentBundle\Entity\Content;
use Faker\Provider\DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FileStorage
 *
 * @Vich\Uploadable
 */
class FileStorage
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @Vich\UploadableField(mapping="content_images", fileNameProperty="fileName", size="fileSize")
     * @var File
     */
    private $file;

    /**
     *
     * @var string
     */
    private $fileName;

    /**
     *
     * @var integer
     */
    private $fileSize;
    /**
     * Get id
     *
     * @return int
     */

    private $alt;

    private $originalName;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $createdAt
     * @return FileStorage
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param \DateTime $updatedAt
     * @return FileStorage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param File|UploadedFile $file
     *
     * @return FileStorage
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;
        if(null == $this->createdAt) {
            $this->createdAt = new \DateTime();
        }
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $fileName
     *
     * @return FileStorage
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param integer $fileSize
     *
     * @return FileStorage
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @param mixed $originalName
     * @return FileStorage
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     * @return FileStorage
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }
}

