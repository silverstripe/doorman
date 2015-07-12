<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Task;
use SplQueue;

class ShellManager implements Manager
{
    /**
     * @var SplQueue
     */
    protected $queue;

    /**
     * Creates the internal queue instance.
     */
    protected function createInternalQueue()
    {
        if (!$this->queue) {
            $this->queue = new SplQueue();
        }
    }

    /**
     * @inheritdoc
     *
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->createInternalQueue();

        $this->queue->enqueue($task);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->createInternalQueue();

        while (!$this->queue->isEmpty()) {
            $task = $this->queue->dequeue();

            $executable = $this->getExecutable();

            $worker = $this->getWorker();

            $command = sprintf(
                "%s %s %s %s %s &",
                $executable,
                $worker,
                base64_encode(serialize($task)),
                "",
                ""
            );

            exec($command);
        }
    }

    /**
     * Get the PHP binary executing the current request.
     *
     * @return string
     */
    protected function getExecutable()
    {
        return PHP_BINDIR . "/php";
    }

    /**
     * Get the worker script, to execute in parallel.
     *
     * @return string
     */
    protected function getWorker()
    {
        return realpath(__DIR__ . "/../../bin/worker.php");
    }
}
