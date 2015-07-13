<?php

namespace AsyncPHP\Doorman;

interface Manager
{
    /**
     * Adds a task to the manager queue.
     *
     * @param Task $task
     */
    public function addTask(Task $task);

    /**
     * Runs a single processing cycle. Will return false when all tasks are complete.
     *
     * @return bool
     */
    public function tick();
}
