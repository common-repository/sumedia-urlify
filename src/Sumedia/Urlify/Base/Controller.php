<?php

namespace Sumedia\Urlify\Base;

abstract class Controller
{
    public function prepare(){}

    abstract public function execute();
}
