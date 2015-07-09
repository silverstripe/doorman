<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Task;
use SplQueue;

class SimpleManager implements Manager
{
    /**
     * @var SplQueue
     */
    protected $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    /**
     * @inheritdoc
     *
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->queue->enqueue($task);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        while (!$this->queue->isEmpty()) {
            $task = $this->queue->dequeue();

            $handler = $task->getHandler();

            $object = new $handler();

            if ($object instanceof Handler) {
                $object->handle($task);
            }
        }
    }
}
