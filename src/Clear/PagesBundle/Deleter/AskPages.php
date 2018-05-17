<?php

namespace Clear\PagesBundle\Deleter;


class AskPages extends AbstractPage
{
    /**
     * @var array
     */
   public $keys = ['img', 'image'];

    /**
     * @param $content
     * @return array
     */
   public function getFileNames($content)
   {
       $image = [$content['image']];
       $img = [];
       foreach ( $content['list'] as $list) {
           if (isset($list['img'])) {
               $img[] = $list['img'];
           }

       }
       return ['img' => $img, 'image' => $image];
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