<?php

namespace Clear\PagesBundle\Model;


use Clear\PagesBundle\Form\HomePagesType;

class HomePages extends PageParams
{
    public function getClassForm()
    {
        return HomePagesType::class;
    }
}