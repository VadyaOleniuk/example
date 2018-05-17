<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 10.10.17
 * Time: 11:30
 */

namespace Clear\ContentBundle\Filter;


abstract class FilterData implements Filter
{
    protected $request;

    protected abstract function getSearchParams($key, $column);

    protected abstract function getSearchParam($key, $column);

    protected abstract function getSearchDateTo($to);

    protected abstract function getSearchDateFrom($from);

    protected abstract function getSearchDateInterval($from, $to);

    protected function getFilter($params)
    {
        foreach ($params as $key => $column){
            if ($this->request->has($key)) {
                $value = $this->request->get($key);
                if(is_array($value)) {
                    if($this->request->get($key)) {
                        $this->getSearchParams($key, $column);
                    }
                }else{
                    $this->getSearchParam($key, $column);
                }
            }
        }
    }

    protected function getSearchDate($from, $to)
    {
        if($from && $to ) {
            if($this->validateDate($from) && $this->validateDate($to)
                && (new \DateTime($from)) <= (new \DateTime($to))
            ) {
                $this->getSearchDateInterval($from.' 00:00:00', $to.' 23:59:59');
                return true;
            }
        }

        if(isset($from)) {
            if($this->validateDate($from)) {
                $this->getSearchDateFrom($from.' 00:00:00');
                return true;
            }
        }

        if(isset($to)) {
            if($this->validateDate($to)) {
                $this->getSearchDateTo($to.' 23:59:59');
                return true;
            }
        }
        if(empty($from) && empty($to)) {
            return true;
        }
        return false;
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}