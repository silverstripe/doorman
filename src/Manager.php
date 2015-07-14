<?php

namespace AsyncPHP\Doorman;

interface Manager
{
    /**
     * @param Task $task
     */
    public function addTask(Task $task);

    /**
     * @return bool
     */
    public function tick();
}
