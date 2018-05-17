<?php
namespace Clear\FileStorageBundle\DataTransformer;

use Clear\FileStorageBundle\Entity\FileStorage;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTransformer implements DataTransformerInterface
{
    private $em;
    private $type;
    const SIZE = 10000000000;

    public function __construct($em,$type )
    {
        $this->em = $em;
        $this->type = $type;
    }

    public function transform($file)
    {
        return null;
    }


    public function reverseTransform($fileUpload)
    {
        if($fileUpload['file'] instanceof UploadedFile) {

            if ($this->validator($fileUpload['file']) === false) {
                return false;
            }
            $storage = new FileStorage();
            $storage->setFile($fileUpload['file']);
            $storage->setOriginalName($fileUpload['file']->getClientOriginalName());
            if(isset($fileUpload['alt'])){
                $storage->setAlt($fileUpload['alt']);
            }
            $this->em->persist($storage);
            return $storage->getFileName();
        }
        else{
            if($fileUpload['file']) {
                $file = $this->em->getRepository('ClearFileStorageBundle:FileStorage')->find($fileUpload['file']);
                if(isset($fileUpload['alt'])){
                    $file->setAlt($fileUpload['alt']);
                }
                $this->em->persist($file);
                return $file->getFileName();
            }
            return  null;
        }
    }

    public function validator($fileUpload)
    {
        /** UploadedFile $fileUpload */
        if ($fileUpload->getSize() > self::SIZE) {

            return false;
        }
        if (!in_array($fileUpload->getMimeType(), $this->type)) {

            return false;
        }

        return true;
    }
}