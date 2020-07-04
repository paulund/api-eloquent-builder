<?php

namespace Paulund\ApiQueryBuilder\Exceptions;

class FilterException extends \Exception
{
    /**
     * FilterException constructor.
     *
     * @param $method
     */
    public function __construct($method)
    {
        parent::__construct('Filter ' . $method . ' not found.');
    }
}