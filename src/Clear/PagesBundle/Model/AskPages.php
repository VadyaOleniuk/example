<?php

namespace Clear\PagesBundle\Model;


use Clear\PagesBundle\Form\AskType;

class AskPages extends PageParams
{
   public function getClassForm()
   {
       return AskType::class;
   }

}