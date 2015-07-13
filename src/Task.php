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

    /**
     * Gets the process ID for this task.
     *
     * @return null|int
     */
    public function getPid();

    /**
     * Sets the process ID for this task.
     *
     * @param int $pid
     */
    public function setPid($pid);
}
