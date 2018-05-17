<?php

namespace Clear\FileStorageBundle\Namer;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * Class FileDirectoryNamer
 * @package Clear\FileStorageBundle\Namer
 */
class FileDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Creates a directory name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param Propertymapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The directory name.
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        return sprintf('%s', $object->getCreatedAt()->format('d-m-y'));
    }
}
