<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 26.10.17
 * Time: 17:02
 */

namespace Clear\PagesBundle\Model;


class BuilderPage
{


    public static function getClass($page, $em)
    {
        switch ($page){
            case 'homepage':
                return new HomePages($em);

            case 'ask':
                return new AskPages($em);

            default:
               return new Pages($em);
        }
    }
}