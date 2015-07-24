<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Shell\BashShell;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Manager\ProcessManager
 */
class ProcessManagerTest extends Test
{
    /**
     * @var ProcessManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = new ProcessManager();
    }

    /**
     * @test
     */
    public function gettersAndSettersWork()
    {
        $this->manager->setLogPath(__DIR__);

        $this->assertEquals(__DIR__, $this->manager->getLogPath());

        $this->assertInstanceOf("AsyncPHP\\Doorman\\Shell", $this->manager->getShell());

        $shell = new BashShell();

        $this->manager->setShell($shell);

        $this->assertEquals($shell, $this->manager->getShell());

        $this->assertInstanceOf("AsyncPHP\\Doorman\\Rules", $this->manager->getRules());

        $rules = new InMemoryRules();

        $this->manager->setRules($rules);

        $this->assertEquals($rules, $this->manager->getRules());
    }

    /**
     * @test
     */
    public function basicRulesAndTasksWork()
    {
        $task1 = new ProcessCallbackTask(function () {
            touch(__DIR__."/task1.temp");

            for ($i = 0; $i < 10; $i++) {
                usleep(50000);
            }

            unlink(__DIR__."/task1.temp");
        });

        $task2 = new ProcessCallbackTask(function () {
            touch(__DIR__."/task2.temp");

            for ($i = 0; $i < 10; $i++) {
                usleep(50000);
            }

            unlink(__DIR__."/task2.temp");
        });

        $rule = new InMemoryRule();
        $rule->setProcesses(1);
        $rule->setMinimumProcessorUsage(0);
        $rule->setMaximumProcessorUsage(100);

        $added = false;

        $this->manager->addRule($rule);
        $this->manager->addTask($task1);

        while ($this->manager->tick()) {
            usleep(50000);

            if (!$added) {
                $this->manager->addTask($task2);
                $added = true;
            }

            if (file_exists(__DIR__."/task1.temp") && file_exists(__DIR__."/task2.temp")) {
                $this->fail("Tasks should not be run concurrently");
            }
        }

        $this->manager->removeRule($rule);
        $this->manager->addTask($task1);
        $this->manager->addTask($task2);

        if ($this->manager->tick()) {
            usleep(50000);

            if (!file_exists(__DIR__."/task1.temp") || !file_exists(__DIR__."/task2.temp")) {
                $this->fail("Tasks should be run concurrently");
            }
        }
    }
}
