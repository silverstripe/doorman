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
    protected $waiting;

    /**
     * @var SplQueue
     */
    protected $running;

    /**
     * @var string
     */
    protected $logPath;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @return bool
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @param string $logPath
     *
     * @return $this
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;

        return $this;
    }

    /**
     * Adds a new concurrency rule.
     *
     * @param int        $processes
     * @param string     $handler
     * @param null|float $cpu
     * @param null|float $memory
     *
     * @return int
     */
    public function addRule($processes, $handler = null, $cpu = null, $memory = null)
    {
        static $index = 0;

        $this->rules[$index] = array($processes, $handler, $cpu, $memory);

        return $index++;
    }

    /**
     * Removes a concurrency rule.
     *
     * @param int $index
     *
     * @return $this
     */
    public function removeRule($index)
    {
        if (isset($this->rules[$index])) {
            unset($this->rules[$index]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @inheritdoc
     *
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->createInternalQueues();

        $this->waiting->enqueue($task);
    }

    /**
     * Creates the internal queue instance.
     */
    protected function createInternalQueues()
    {
        if (!$this->waiting) {
            $this->waiting = new SplQueue();
        }

        if (!$this->running) {
            $this->running = new SplQueue();
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->createInternalQueues();

        while (!$this->waiting->isEmpty()) {
            /** @var Task $task */
            $task = $this->waiting->dequeue();

            $command = $this->getCommandForTask($task);

            $pid = exec($command);

            $task->setPid($pid);

            $this->running->enqueue($task);
        }
    }

    /**
     * Generate a worker command for a task.
     *
     * @param Task $task
     *
     * @return string
     */
    protected function getCommandForTask(Task $task)
    {
        return sprintf("%s %s %s %s %s & echo $!",
            $this->getExecutable(),
            $this->getWorker(),
            $this->getTaskString($task),
            $this->getStdOut(),
            $this->getStdErr()
        );
    }

    /**
     * Encodes the task into a single line.
     *
     * @param Task $task
     *
     * @return string
     */
    protected function getTaskString(Task $task)
    {
        return base64_encode(serialize($task));
    }

    /**
     * @return string
     */
    protected function getStdOut()
    {
        if ($this->logPath) {
            return ">> " . $this->logPath . "/stdout.log";
        }

        return "> /dev/null";
    }

    /**
     * @return string
     */
    protected function getStdErr()
    {
        if ($this->logPath) {
            return "2>> " . $this->logPath . "/stderr.log";
        }

        return "2> /dev/null";
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
