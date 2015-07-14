<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Rules;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Task;

class ProcessManager implements Manager
{
    /**
     * @var array
     */
    protected $waiting = array();

    /**
     * @var array
     */
    protected $running = array();

    /**
     * @var string
     */
    protected $logPath;

    /**
     * @var Rules
     */
    protected $rules;

    public function __construct()
    {
        $this->rules = new InMemoryRules();
    }

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
        $waiting = array();
        $running = array();

        foreach ($this->waiting as $task) {
            if (!$this->canRunTask($task)) {
                $waiting[] = $task;
                continue;
            }

            $command = $this->getCommandForTask($task);

            $pid = exec($command);

            if ($task instanceof Process) {
                $task->setId($pid);
            }

            $this->running[] = $task;
        }

        foreach ($this->running as $task) {
            if (!$this->canRemoveTask($task)) {
                $running[] = $task;
            }
        }

        $this->waiting = $waiting;
        $this->running = $running;

        return count($this->waiting) > 0 || count($this->running) > 0;
    }

    /**
     * Checks whether another task can be run at this time.
     *
     * @param Task $task
     *
     * @return bool
     */
    protected function canRunTask(Task $task)
    {
        $processes = array_filter($this->running, function (Task $task) {
            return $task instanceof Process;
        });

        if (count($processes) < 1) {
            return true;
        }

        $stats = $this->getStatsForProcesses($processes);

        $processor = (float) array_sum(array_map(function ($stat) {
            return (float) $stat[1];
        }, $stats));

        $memory = (float) array_sum(array_map(function ($stat) {
            return (float) $stat[2];
        }, $stats));

        $siblings = array_filter($processes, function (Task $next) use ($task) {
            return $next->getHandler() === $task->getHandler();
        });

        $siblingStats = $this->getStatsForProcesses($siblings);

        $siblingProcessor = (float) array_sum(array_map(function ($stat) {
            return (float) $stat[1];
        }, $siblingStats));

        $siblingMemory = (float) array_sum(array_map(function ($stat) {
            return (float) $stat[2];
        }, $siblingStats));

        $profile = new InMemoryProfile();
        $profile->setProcesses($processes);
        $profile->setProcessorLoad($processor);
        $profile->setMemoryLoad($memory);
        $profile->setSiblingProcesses($siblings);
        $profile->setSiblingProcessorLoad($siblingProcessor);
        $profile->setSiblingMemoryLoad($siblingMemory);

        return $this->rules->canRunTask($task, $profile);
    }

    /**
     * Gets the statistics for all running processes.
     *
     * @param array $processes
     *
     * @return array
     */
    protected function getStatsForProcesses(array $processes)
    {
        $stats = array();

        foreach ($processes as $process) {
            $command = $this->getCommandForStats($process);

            $result = exec($command);

            if (empty($result)) {
                continue;
            }

            $stats[] = preg_split("/\s+/", $result);
        }

        return $stats;
    }

    /**
     * Gets the command for process statistics.
     *
     * @param Process $process
     *
     * @return string
     */
    protected function getCommandForStats(Process $process)
    {
        return sprintf("ps -o %s -p %s",
            "pid,%cpu,%mem,state,start",
            $process->getId()
        );
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
     * Checks whether a running process can be removed (has stopped running).
     *
     * @param Task $task
     *
     * @return bool
     */
    protected function canRemoveTask(Task $task)
    {
        if (!$task instanceof Process) {
            return true;
        }

        $processes = array_filter($this->running, function (Task $task) {
            return $task instanceof Process;
        });

        if (count($processes) < 1) {
            return true;
        }

        $found = false;
        $stats = $this->getStatsForProcesses($processes);

        foreach ($stats as $stat) {
            if ($stat[0] === $task->getId()) {
                $found = true;
            }
        }

        return !$found;
    }

    /**
     * @param Rule $rule
     *
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        $this->rules->addRule($rule);

        return $this;
    }

    /**
     * @param Rule $rule
     *
     * @return $this
     */
    public function removeRule(Rule $rule)
    {
        $this->rules->removeRule($rule);

        return $this;
    }
}
