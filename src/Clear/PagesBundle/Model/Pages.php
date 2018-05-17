<?php

namespace Clear\PagesBundle\Model;


use Clear\PagesBundle\Form\PageType;

class Pages extends PageParams
{
    public function getClassForm()
    {
        return PageType::class;
    }

    function getParams($manager)
    {
        return [];
    }

    public function getStatus($page)
    {
        return true;
    }

    public function isDelete($page){
        return true;
    }
}