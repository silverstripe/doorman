<?php

namespace AsyncPHP\Doorman;

interface Manager
{
    /**
     * Adds a task to be handled.
     *
     * @param Task $task
     */
    public function addTask(Task $task);

    /**
     * Executes a single processing cycle. This should be run repeatedly, and will return false when there are no more running or waiting processes.
     *
     * @return bool
     */
    public function tick();
}
