<?php

namespace Clear\SearchBundle\Searcher;

use Elastica\Query\Match;
use Elastica\Query\MultiMatch;
use Elastica\Query\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;

abstract class AbstractSearcher implements SearcherInterface
{
    protected $boolQuery;
    protected $sizeSearch;
    protected $userRights;

    public function __construct()
    {
        $this->boolQuery = new BoolQuery();
    }

    public abstract function search();


    protected function getSearchParam($type, $key)
    {
        $match = new Match();
        $match->setField($type, $key);
        return $match;
    }

    protected function getSize($page)
    {
        $query = new Query();
        $query->setQuery($this->boolQuery);
        $query->setSize($this->sizeSearch);
        $query->setFrom(($page - 1) * $this->sizeSearch);
        return $query;
    }

    protected function getSearchParams($type, $keys)
    {
        $tags = new Terms();
        $tags->setTerms($type, $keys);
        return $tags;
    }

    protected function getSearchParamTerm($type, $keys)
    {
        $tags = new Query\Term();
        $tags->setTerm($type, $keys);
        return $tags;
    }

    protected function getContent($search)
    {
        $multiMatch = new MultiMatch();
        $multiMatch->setQuery($search);
        $multiMatch->setFields(['title^3', 'contentList^1']);

        $multiMatch->setType('phrase_prefix');
        $this->boolQuery->addMust($multiMatch);
    }
}