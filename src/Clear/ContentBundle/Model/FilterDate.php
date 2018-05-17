<?php

namespace Clear\ContentBundle\Model;


use Symfony\Component\HttpFoundation\ParameterBag;

class FilterDate
{
    private $params;
    public function __construct($params)
    {
        $this->params = $params;
    }


    public function getFilterDate(&$query, ParameterBag $request)
    {

        if($request->get('from')) {
            $from = $request->get('from').' 00:00:00';
            if($this->validateDate($from)) {
                $query->andWhere($this->params.' > :from')
                    ->setParameter('from', $from);
            }else{
                throw new HttpException(400, "Bad Request");
            }
        }

        if($request->get('to')) {
            $to = $request->get('to').' 23:59:59';
            if($this->validateDate($to)) {
                $query->andWhere($this->params.' < :to')
                    ->setParameter('to', $to);
            }else{
                throw new HttpException(400, "Bad Request");
            }
        }

        if($request->get('from') && $request->get('to') ) {
            $from = $request->get('from').' 00:00:00';
            $to = $request->get('to').' 23:59:59';
            if($this->validateDate($from) && $this->validateDate($to)
                && (new \DateTime($from)) < (new \DateTime($to))
            ) {
                $query->andWhere($this->params.' > :from AND '.$this->params.' < :to')
                    ->setParameter('from', $from)
                    ->setParameter('to', $to);
            }else{
                throw new HttpException(400, "Bad Request");
            }
        }

    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}