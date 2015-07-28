<?php

namespace AsyncPHP\Doorman;

use Serializable;

interface Task extends Serializable
{
    /**
     * Gets the name of the handler class. This class will be used to handle this task.
     *
     * @return string
     */
    public function getHandler();

    /**
     * Gets the data collected in this task.
     *
     * @return array
     */
    public function getData();

    /**
     * Instructs a manager to ignore any rules that would prevent this task from being immediately handled.
     *
     * @return bool
     */
    public function ignoresRules();

    /**
     * Instructs a manager to stop all tasks of the same type before running this task.
     *
     * @return bool
     */
    public function stopsSiblings();

    /**
     * Check if this task is able to be run
     *
     * @return bool
     */
    public function canRunTask();
}
