<?php

namespace Clear\PagesBundle\Deleter;


class TypeInit
{
    public static function getPage($page, $manager, $container)
    {
        switch ($page){
            case 'homepage':
                return new HomePages($manager, $container);

            case 'ask':
                return new AskPages($manager, $container);

            case 'content':
                return new Content($manager, $container);

            default:
                return new Pages($manager, $container);
        }
    }
}