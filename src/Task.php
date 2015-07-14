<?php

namespace AsyncPHP\Doorman;

use Serializable;

interface Task extends Serializable
{
    /**
     * @return string
     */
    public function getHandler();

    /**
     * @return array
     */
    public function getData();
}
