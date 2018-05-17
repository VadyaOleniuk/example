<?php

namespace Clear\ContentBundle\Model;

class ContentList
{
    public static function getContentList($content)
    {
        $content = preg_replace ('/<img.*>/Uis', '', $content);

        return $content;
    }

}