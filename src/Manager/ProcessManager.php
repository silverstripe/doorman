<?php

namespace AsyncPHP\Doorman\Manager;

use AsyncPHP\Doorman\Cancellable;
use AsyncPHP\Doorman\Expires;
use AsyncPHP\Doorman\Manager;
use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile;
use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Rules;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Shell;
use AsyncPHP\Doorman\Shell\BashShell;
use AsyncPHP\Doorman\Task;
use SplObjectStorage;

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
     * @var null|SplObjectStorage
     */
    protected $timings = null;

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
     * @var null|string
     */
    protected $binary;

    /**
     * @var null|string
     */
    protected $worker;

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
        if (!$this->timings instanceof SplObjectStorage) {
            $this->timings = new SplObjectStorage();
        }

        $waiting = array();
        $running = array();

        foreach ($this->waiting as $task) {
            if ($this->isTaskCancelled($task)) {
                continue;
            }
            
            if (!$this->canRunTask($task)) {
                $waiting[] = $task;
                continue;
            }

            if ($task->stopsSiblings()) {
                $this->stopSiblingTasks($task);
            }

            $binary = $this->getBinary();
            $worker = $this->getWorker();
            $stdout = $this->getStdOut();
            $stderr = $this->getStdErr();

            if ($task instanceof Expires) {
                $this->timings[$task] = time();
            }

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
     * Stops sibling processes of a task.
     *
     * @param Task $task
     *
     * @return $this
     */
    protected function stopSiblingTasks(Task $task)
    {
        $handler = $task->getHandler();

        foreach ($this->running as $task) {
            if ($task->getHandler() === $handler && $task instanceof Process) {
                $this->getShell()->exec("kill -9 %s", array(
                    $task->getId(),
                ));
            }
        }

        return $this;
    }

    /**
     * Checks whether a new task can be run.
     *
     * @param Task $task
     *
     * @return bool
     */
    protected function canRunTask(Task $task)
    {
        if(!$task->canRunTask()) {
            return false;
        }

        if ($task->ignoresRules()) {
            return true;
        }

        $processes = array_filter($this->running, function (Task $task) {
            return $task instanceof Process;
        });

        if (count($processes) < 1) {
            return true;
        }

        $profile = $this->getProfileForProcesses($task, $processes);

        return $this->getRules()->canRunTask($task, $profile);
    }

    /**
     * Gets the load profile related to a task.
     *
     * @param Task  $task
     * @param array $processes
     *
     * @return Profile
     */
    protected function getProfileForProcesses(Task $task, array $processes)
    {
        $stats = $this->getStatsForProcesses($processes);

        $siblingProcesses = array_filter($processes, function (Task $next) use ($task) {
            return $next->getHandler() === $task->getHandler();
        });

        $siblingStats = $this->getStatsForProcesses($siblingProcesses);

        $profile = $this->newProfile();

        $profile->setProcesses($processes);
        $profile->setProcessorLoad(min(100, array_sum(array_column($stats, 1))));
        $profile->setMemoryLoad(min(100, array_sum(array_column($stats, 2))));

        $profile->setSiblingProcesses($siblingProcesses);
        $profile->setSiblingProcessorLoad(min(100, array_sum(array_column($siblingStats, 1))));
        $profile->setSiblingMemoryLoad(min(100, array_sum(array_column($siblingStats, 2))));

        return $profile;
    }

    /**
     * Gets processor and memory stats for a list of processes.
     *
     * @param Process[] $processes
     *
     * @return array
     */
    protected function getStatsForProcesses(array $processes)
    {
        $stats = array();

        foreach ($processes as $process) {
            $result = $this->getShell()->exec("ps -o pid,%%cpu,%%mem,state,start -p %s", array(
                $process->getId(),
            ));

            if (trim($result) === "") {
                continue;
            }

            $stats[] = preg_split("/\s+/", trim($result));
        }

        return $stats;
    }

    /**
     * Gets or creates a Shell instance.
     *
     * @return Shell
     */
    public function getShell()
    {
        if ($this->shell === null) {
            $this->shell = $this->newShell();
        }

        return $this->shell;
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
     * Creates a new Shell instance.
     *
     * @return Shell
     */
    protected function newShell()
    {
        return new BashShell();
    }

    /**
     * Creates a new Profile instance.
     *
     * @return Profile
     */
    protected function newProfile()
    {
        return new InMemoryProfile();
    }

    /**
     * Gets or creates a new Rules instance.
     *
     * @return Rules
     */
    public function getRules()
    {
        if ($this->rules === null) {
            $this->rules = $this->newRules();
        }

        return $this->rules;
    }

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
     * Creates a new Rules instance.
     *
     * @return Rules
     */
    protected function newRules()
    {
        return new InMemoryRules();
    }

    /**
     * @param string $binary
     *
     * @return $this
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * Gets the path of the PHP runtime.
     *
     * @return string
     */
    public function getBinary()
    {
        if ($this->binary === null) {
            $this->binary = PHP_BINDIR."/php";
        }

        return $this->binary;
    }

    /**
     * @param string $worker
     *
     * @return $this
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * Gets the path of the worker script.
     *
     * @return string
     */
    public function getWorker()
    {
        if ($this->worker === null) {
            $this->worker = realpath(__DIR__."/../../bin/worker.php");
        }

        return $this->worker;
    }

    /**
     * Gets the path to write stdout to.
     *
     * @return string
     */
    protected function getStdOut()
    {
        if ($this->getLogPath() !== null) {
            return ">> ".$this->getLogPath()."/stdout.log";
        }

        return "> /dev/null";
    }

    /**
     * @return null|string
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
     * Gets the path to write stderr to.
     *
     * @return string
     */
    protected function getStdErr()
    {
        if ($this->getLogPath() !== null) {
            return "2>> ".$this->getLogPath()."/stderr.log";
        }

        return "2> /dev/null";
    }

    /**
     * Gets a string representation of a task, to pass to the worker script.
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
     * Checks whether a task can be removed from the list of running processes.
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
        
        if ($this->isTaskExpired($task) || $this->isTaskCancelled($task)) {
            $this->killTask($task);
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
     * Check if the given task is expired
     *
     * @param Task $task
     * @return boolean
     */
    protected function isTaskExpired(Task $task) {
        if ($task instanceof Expires) {
            $expiresIn = $task->getExpiresIn();
            $startedAt = $this->timings[$task];

            if($expiresIn > 0 && (time() - $startedAt) >= $expiresIn) {
                return $task->shouldExpire($startedAt);
            }
        }
        return false;
    }

    /**
     * Check if the given task is cancelled
     * 
     * @param Task $task
     * @return bool
     */
    protected function isTaskCancelled(Task $task)
    {
        if ($task instanceof Cancellable) {
            return $task->isCancelled();
        }
        return false;
    }

    /**
     * Revoke any background processes attached to this task
     *
     * @param Task $task
     * @return bool If the process was killed
     */
    protected function killTask(Task $task)
    {
        if ($task instanceof Process) {
            $this->getShell()->exec("kill -9 %s", array(
                $task->getId(),
            ));
            return true;
        }
        return false;
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
