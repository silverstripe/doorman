<?php

namespace AsyncPHP\Doorman;

use Serializable;

interface Task extends Serializable
{
    /**
     * Get the name of the handler class for this task.
     *
     * @return string
     */
    public function getHandler();

    /**
     * Get the data associated with this task.
     *
     * @return array
     */
    public function getData();
}
