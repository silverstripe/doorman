<?php

namespace AsyncPHP\Doorman\Handler;

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;

class SimpleHandler implements Handler
{
    /**
     * @inheritdoc
     *
     * @param Task $task
     */
    public function handle(Task $task)
    {
        $data = $task->getData();

        // TODO handle malformed data

        $callback = $data["callback"];
        $callback();
    }
}