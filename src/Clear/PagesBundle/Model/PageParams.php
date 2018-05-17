<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 27.10.17
 * Time: 13:06
 */

namespace Clear\PagesBundle\Model;


abstract class PageParams
{
    public function __construct($em)
    {
        $this->em = $em;
    }

    abstract function getClassForm();

    function getParams($manager)
    {
        return ["entityManager" => $manager];
    }

    public function getStatus($page)
    {

        if($page->getStatus() === 1){
            $draftPage = $this->em
                ->update("ClearPagesBundle:Pages","pages")
                ->set("pages.status", ":new")
                ->where("pages.status = :old AND pages.type = :type")
                ->setParameter("new", false)
                ->setParameter("old", true)
                ->setParameter("type", $page->getType())
                ->getQuery();
            $draftPage->execute();
        }
    }

    public function isDelete($page)
    {
        if($page->getStatus()){
            return false;
        }
        return true;
    }
}