<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Rules;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Shell;
use AsyncPHP\Doorman\Shell\BashShell;
use AsyncPHP\Doorman\Task;

class ProcessManager implements Manager
{
    /**
     * @var Task[]
     */
    protected $waiting = array();

    /**
     * @var Task[]
     */
    protected $running = array();

    /**
     * @var null|string
     */
    protected $logPath;

    /**
     * @var null|Rules
     */
    protected $rules;

    /**
     * @var null|Shell
     */
    protected $shell;

    /**
     * @param Rules $rules
     *
     * @return $this
     */
    public function setRules(Rules $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return Rules
     */
    public function getRules()
    {
        if ($this->rules === null) {
            $this->rules = new InMemoryRules();
        }

        return $this->rules;
    }

    /**
     * @param Shell $shell
     *
     * @return $this
     */
    public function setShell(Shell $shell)
    {
        $this->shell = $shell;

        return $this;
    }

    /**
     * @return Shell
     */
    public function getShell()
    {
        if ($this->shell === null) {
            $this->shell = new BashShell();
        }

        return $this->shell;
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
     * @return bool
     */
    public function getLogPath()
    {
        return $this->logPath;
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

            $binary = $this->getBinary();
            $worker = $this->getWorker();
            $stdout = $this->getStdOut();
            $stderr = $this->getStdErr();

            $pid = $this->getShell()->exec("{$binary} {$worker} %s {$stdout} {$stderr} & echo $!", array(
                $this->getTaskString($task),
            ));

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

        return $this->getRules()->canRunTask($task, $profile);
    }

    /**
     * @param Process[] $processes
     *
     * @return array
     */
    protected function getStatsForProcesses(array $processes)
    {
        $stats = array();

        foreach ($processes as $process) {
            $result = $this->getShell()->exec("ps -o pid,%%cpu,%%mem,state,start -p %s", array(
                $process->getId()
            ));

            if (empty($result)) {
                continue;
            }

            $stats[] = preg_split("/\s+/", $result);
        }

        return $stats;
    }

    /**
     * @return string
     */
    protected function getBinary()
    {
        return PHP_BINDIR . "/php";
    }

    /**
     * @return string
     */
    protected function getWorker()
    {
        return realpath(__DIR__ . "/../../bin/worker.php");
    }

    /**
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
        if ($this->getLogPath()) {
            return ">> " . $this->getLogPath() . "/stdout.log";
        }

        return "> /dev/null";
    }

    /**
     * @return string
     */
    protected function getStdErr()
    {
        if ($this->getLogPath()) {
            return "2>> " . $this->getLogPath() . "/stderr.log";
        }

        return "2> /dev/null";
    }

    /**
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
        $this->getRules()->addRule($rule);

        return $this;
    }

    /**
     * @param Rule $rule
     *
     * @return $this
     */
    public function removeRule(Rule $rule)
    {
        $this->getRules()->removeRule($rule);

        return $this;
    }
}
