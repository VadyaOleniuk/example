<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 10.10.17
 * Time: 11:37
 */

namespace Clear\ContentBundle\Filter;

use Symfony\Component\HttpKernel\Exception\HttpException;

class FilterOrm extends FilterData
{
    /**
     * Column name
     *
     * @var array
     */
    private $params = ['isArticle' => [ 'column'=>'c.isArticle'],
        'tags' => ['table'=>'c.tags', 'column'=>'id'],
        'categories' => ['table'=>'c.categories', 'column'=>'id'],
        'contentType' => ['table'=>'c.contentType', 'column'=>'id'],
        'roles' => ['table'=>'c.roles', 'column'=>'id'],
        'companies' => ['table'=>'c.companies', 'column'=>'id'],
        'status' => ['column'=>'c.status'],
        'authorId' => ['column'=>'c.user'],
        'language' => ['column'=>'c.language'],
    ];

    private $query;

    private $columnDate;

    public function __construct($query, $request, $columnDate='c.updatedAt')
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
        $table='';
        if(isset($column['table'])) {
            $table = $key.'.';
            $this->query->leftJoin($column['table'], $key);
        }
        $this->query->andWhere($table.$column['column'].' IN (:'.$key.')')
            ->setParameter($key, $this->request->get($key));
    }

    public function getSearchParam($key, $column)
    {
        $table = '';
        if(isset($column['table'])) {
            $table = $key.'.';
            $this->query->leftJoin($column['table'], $key);
        }
        $this->query->andWhere($table.$column['column'].' =:'.$key.'')
            ->setParameter($key, $this->request->get($key));
    }

    protected function getSearchDateTo($to)
    {
        $this->query->andWhere($this->columnDate.' < :to')
            ->setParameter('to', $to);
    }

    protected function getSearchDateFrom($from)
    {
        $this->query->andWhere($this->columnDate.' > :from')
            ->setParameter('from', $from);
    }

    protected function getSearchDateInterval($from, $to)
    {
        $this->query->andWhere($this->columnDate.' > :from AND c.updatedAt < :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to);
    }
}