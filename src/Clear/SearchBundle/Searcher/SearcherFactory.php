<?php

namespace Clear\SearchBundle\Searcher;

use Symfony\Component\HttpKernel\Exception\HttpException;

class SearcherFactory
{
    private $securityContext;
    private $role;
    private $finders;


    public function __construct($securityContext, $role, array $finders)
    {
        $this->securityContext = $securityContext;
        $this->finders = $finders;
        $this->role = $role->getToken()->getUser()->getRoleUsers();
    }

    public function getSearcher()
    {
        return $this->finders['admin_finder'];
    }

}