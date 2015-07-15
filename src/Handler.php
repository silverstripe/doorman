<?php

namespace AsyncPHP\Doorman;

interface Handler
{
    /**
     * @param Task $task
     */
    public function handle(Task $task);
}
