<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 11.10.17
 * Time: 14:13
 */

namespace Clear\SearchBundle\Searcher;


use Clear\ContentBundle\Filter\FilterData;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FilterElastic extends FilterData
{
    /**
     * Column name
     *
     * @var array
     */
    private $params = ['language' => 'language.id',
        'tags' => 'tags.id',
        'categories' => 'categories.id',
        'contentType' => 'contentType.id',
        'roles' => 'roles.id',
        'companies' => 'companies.id',
        'status' => 'status',
        'authorId' => 'user.id',
        'language' => 'language.id',
        'isArticle' => 'isArticle'
    ];

    private $query;

    private $columnDate;

    public function __construct($query, $request, $columnDate='updatedAt')
    {
        $this->query = $query;
        $this->request = $request;
        $this->columnDate = $columnDate;
    }

    public function search()
    {
        $this->getFilter($this->params);
        if(!$this->getSearchDate($this->request->get('from'), $this->request->get('to'))) {
            throw new HttpException(400, "Bad Request");
        }
        return $this->query;
    }

    public function getSearchParams($key, $column)
    {
        $filter = new Terms();
        $filter->setTerms($column, $this->request->get($key));
        return $this->query->addMust($filter);
    }

    public function getSearchParam($key, $column)
    {
        $filter = new Term();
        $filter->setTerm($column, $this->request->get($key));
        return $this->query->addMust($filter);
    }

    protected function getSearchDateTo($to)
    {
        $this->query->addMust(
            new Range(
                $this->columnDate, [
                "lt" => $to,
                "format" => "yyyy-MM-dd HH:mm:ss"
                ]
            )
        );
    }

    protected function getSearchDateFrom($from)
    {
        $this->query->addMust(
            new Range(
                $this->columnDate, [
                "gt" => $from,
                "format" => "yyyy-MM-dd HH:mm:ss"
                ]
            )
        );
    }

    protected function getSearchDateInterval($from, $to)
    {
        $this->query->addMust(
            new Range(
                $this->columnDate, [
                "gt" => $from,
                "lt" => $to,
                "format" => "yyyy-MM-dd HH:mm:ss"
                ]
            )
        );
    }
}