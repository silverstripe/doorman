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
     * Runs the main loop, to process tasks.
     */
    public function run();
}
