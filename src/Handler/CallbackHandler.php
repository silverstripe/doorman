<?php

namespace AsyncPHP\Doorman\Handler;

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;

class CallbackHandler implements Handler
{
    /**
     * @inheritdoc
     *
     * @param Task $task
     */
    public function handle(Task $task)
    {
        $data = $task->getData();

        if (isset($data["closure"])) {
            $closure = $data["closure"];
            $closure();
        }
    }
}
