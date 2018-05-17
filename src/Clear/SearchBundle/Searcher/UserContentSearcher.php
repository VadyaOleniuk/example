<?php

namespace Clear\SearchBundle\Searcher;

use Elastica\Index;
use Elastica\Query;
use Elastica\Response;
use Elastica\ResultSet;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserContentSearcher extends AbstractSearcher
{
    private $request;
    private $finder;

    public function __construct(RequestStack $request, $userRights, TransformedFinder $finder, $size)
    {
        $this->finder = $finder;
        $this->request = $request->getCurrentRequest();
        $this->userRights = $userRights->getToken()->getUser();

        if($this->request->query->has('size')) {
            $this->sizeSearch = $this->request->query->get('size');
        }else{
            $this->sizeSearch =$size;
        }
        parent::__construct();
    }

    public function search()
    {
        if(!$this->request->query->has('search')) {
            throw new HttpException(400, "Service does not exist");
        }
        $this->getContent($this->request->query->get('search'));
        $this->setNoContent();
        if($this->getContentRights()) {
            try {
                if (!$data['items'] = $this->finder->find($this->getSize($this->request->query->getInt("page", 1)))) {
                    return ['success' => 'No search results'];
                }
            } catch (\Exception $exception) {
                throw new HttpException(503, "Service is temporarily unavailable");
            }
            $query = new Query();
            $query->setQuery($this->boolQuery);

            $data['page'] = $this->finder->find(
                $this->getSize($this->request->query->getInt("page", 1) + 1)
            ) ? true : false;
            return $data;
        }
        throw new HttpException(403, "No Rights");
    }

    private function getContentRights()
    {
        $filter = new FilterElastic($this->boolQuery, $this->request->query);
        $this->boolQuery = $filter->search();
        if($this->checkRole('Admin')) {
            return true;
        }

        $roles=[];
        foreach ($this->userRights->getRoleUsers() as $role){
            $roles[] = $role->getId();
        }

        if($this->checkRole('Client_admin') ) {

            $this->boolQuery->addMust($this->getRightSearch($roles, false));
            return true;
        }

        if($this->checkRole('Assured_user') ||  $this->checkRole('Partner_user') || $this->checkRole('User')) {
            $this->boolQuery->addMust($this->getRightSearch($roles, true));
            return true;
        }
        return false;
    }

    private function checkRole($role)
    {
        foreach ($this->userRights->getRoleUsers() as $value){
            if($value->getName() == $role) {
                return true;
            }
        }
        return false;
    }

    private function getRightSearch($roles, $user = true)
    {
        $rightSearch = new Query\BoolQuery();
        $rightRole = clone $rightSearch;
        $rightCompany = clone $rightSearch;
        $basicRight = clone $rightSearch;
        $noRight = clone $rightSearch;

        $roles = $this->getSearchParams("roles.id", $roles);
        $roleExists = new Query\Exists('roles.id');
        $companyExists = new Query\Exists('companies.id');

        $rightRole->addMust($roles);
        $rightRole->addMust((new Query\BoolQuery())->addMustNot($companyExists));

        $rightSearch->addShould($rightRole);
        $basicRight->addMust($roles);

        if($this->userRights->getCompany()) {
            $company = $this->getSearchParams("companies.id", [$this->userRights->getCompany()->getId()]);
            $basicRight->addMust($company);
            $rightCompany->addMust($company);
            $rightCompany->addMust((new Query\BoolQuery())->addMustNot($roleExists));
            $rightSearch->addShould($rightCompany);
        }

        $noRight->addMustNot($companyExists);
        $noRight->addMustNot($roleExists);
        $rightSearch->addShould($basicRight);
        $rightSearch->addShould($noRight);

        $status =  new  Query\BoolQuery();
        $status->addMust($rightSearch);

        if($user) {
            $status->addMust($this->getSearchParamTerm("status", 1));
        }

        return $status;
    }

    private function setNoContent()
    {
        if($this->request->query->has('content')) {
            $this->boolQuery->addMustNot($this->getSearchParamTerm("id", $this->request->query->get('content')));
        }
    }
}