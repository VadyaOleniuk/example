<?php

namespace Clear\PagesBundle\Deleter;


class HomePages extends AbstractPage
{

    public $keys = ['imgNormal', 'imgContrast', 'image'];

    public function getFileNames($content)
    {
        $imgNormal = [];
        $imgContrast = [];
        $image = [$content['image']];
        foreach ( $content['articles'] as $aticle) {
            if (isset($aticle['imgNormal'])) {
                $imgNormal[] = $aticle['imgNormal'];
            }

            if (isset($aticle['imgContrast'])) {
                $imgContrast[] = $aticle['imgContrast'];
            }
        }
        return ['imgNormal' => $imgNormal, 'imgContrast' => $imgContrast , 'image' => $image];
    }

    public function getFileNamesForDelete($oldNames, $newNames)
    {
        return $this->getNeedDelete($this->keys, $oldNames, $newNames);
    }

    public function deletePageFiles($needDelete)
    {
        foreach ($this->keys as $key) {
            $this->deleteFiles($needDelete[$key]);
        }
    }
}