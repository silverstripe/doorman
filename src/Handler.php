<?php

namespace AsyncPHP\Doorman;

interface Handler
{
    /**
     * @todo description
     *
     * @param Task $task
     */
    public function handle(Task $task);
}
