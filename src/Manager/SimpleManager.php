<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Task;

class SimpleManager implements Manager
{
    /**
     * @var array
     */
    protected $waiting = array();

    /**
     * @inheritdoc
     *
     * @param Task $task
     *
     * @return $this
     */
    public function addTask(Task $task)
    {
        $this->waiting[] = $task;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function tick()
    {
        foreach ($this->waiting as $task) {
            /** @var Task $task */
            $handler = $task->getHandler();

            $object = new $handler();

            if ($object instanceof Handler) {
                $object->handle($task);
            }
        }

        return false;
    }
}
