<?php

namespace Clear\PagesBundle\Deleter;


class Pages extends AbstractPage
{
    public function getFileNames($content)
    {
        return [];
    }

    public function getFileNamesForDelete($oldNames, $newNames)
    {
        return [];
    }

    public function deletePageFiles($needDelete)
    {
        return [];
    }
}