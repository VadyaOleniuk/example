<?php

namespace Clear\PagesBundle\Deleter;


class Content extends AbstractPage
{
    public function getFileNames($contentTypes)
    {
        $files = [];
        foreach ($contentTypes as $contentType) {
            if (isset($contentType['file'])) {
                $files[] =  $contentType['file'];
            }
        }

        return $files;
    }

    public function getFileNamesForDelete($oldNames, $newNames)
    {
        return array_diff ($oldNames, $newNames);
    }

    public function deletePageFiles($needDelete)
    {
        $this->deleteFiles($needDelete);
    }
}