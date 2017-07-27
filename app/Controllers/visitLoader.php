<?php

namespace app\Controllers;

class visitLoader
{
    protected $method;
    protected $transfVal;
    protected $obj;

    public function __construct($obj, $method, $transfVal)
    {
        $this->obj = $obj;
        $this->method = (string) $method;
        $this->transfVal = $transfVal;
    }

    public function runQuery()
    {
        return $this->obj->{$this->method}($this->transfVal);
    }
}
