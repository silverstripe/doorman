<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Task;

class ShellManager implements Manager
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
     * Adds a new concurrency rule and returns an identifier for the rule.
     *
     * @param Rule $rule
     *
     * @return int
     */
    public function addRule(Rule $rule)
    {
        static $index = 0;

        $this->rules[$index] = $rule;

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

        $rules = $this->getRulesForTask($task);

        if (count($rules) > 0) {
            /** @var Rule $rule */
            foreach ($rules as $rule) {
                if ($rule->getHandler() === null && $this->withinConstraints($rule, $processor, $memory) && $this->withinSiblingConstraints($rule, $siblingProcessor, $siblingMemory) && count($processes) >= $rule->getProcesses()) {
                    return false;
                }

                if ($rule->getHandler() === $task->getHandler() && $this->withinConstraints($rule, $processor, $memory) && $this->withinSiblingConstraints($rule, $siblingProcessor, $siblingMemory) && count($siblings) >= $rule->getProcesses()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks whether all process and memory usage is within the constraints of a rule.
     *
     *
     * @param Rule  $rule
     * @param float $processor
     * @param float $memory
     *
     * @return bool
     */
    protected function withinConstraints(Rule $rule, $processor, $memory)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumProcessorUsage()) {
            $minimumProcessor = $rule->getMinimumProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMinimumProcessorUsage()) {
            $maximumProcessor = $rule->getMaximumProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumMemoryUsage()) {
            $minimumMemory = $rule->getMinimumMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMinimumMemoryUsage()) {
            $maximumMemory = $rule->getMaximumMemoryUsage();
        }

        return $processor >= $minimumProcessor && $processor <= $maximumProcessor && $memory >= $minimumMemory && $memory <= $maximumMemory;
    }

    /**
     * Checks whether sibling process and memory usage is within the constraints of a rule.
     *
     *
     * @param Rule  $rule
     * @param float $processor
     * @param float $memory
     *
     * @return bool
     */
    protected function withinSiblingConstraints(Rule $rule, $processor, $memory)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumSiblingProcessorUsage()) {
            $minimumProcessor = $rule->getMinimumSiblingProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMinimumSiblingProcessorUsage()) {
            $maximumProcessor = $rule->getMaximumSiblingProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumSiblingMemoryUsage()) {
            $minimumMemory = $rule->getMinimumSiblingMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMinimumSiblingMemoryUsage()) {
            $maximumMemory = $rule->getMaximumSiblingMemoryUsage();
        }

        return $processor >= $minimumProcessor && $processor <= $maximumProcessor && $memory >= $minimumMemory && $memory <= $maximumMemory;
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
     * Gets the rules which apply to a task.
     *
     * @param Task $task
     *
     * @return array
     */
    protected function getRulesForTask(Task $task)
    {
        $rules = array();

        /** @var Rule $rule */
        foreach ($this->rules as $rule) {
            if ($rule->getHandler() === null || $rule->getHandler() === $task->getHandler()) {
                $rules[] = $rule;
            }
        }

        return $rules;
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
}
