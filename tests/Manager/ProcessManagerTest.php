<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Manager\Fixture\TestTask1;
use AsyncPHP\Doorman\Tests\Manager\Fixture\TestTask2;
use AsyncPHP\Doorman\Tests\Manager\Fixture\TestTask3;
use AsyncPHP\Doorman\Tests\Test;
use Exception;

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
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->manager = null;

        parent::tearDown();
    }

    /**
     * @test
     */
    public function handlesProcessCallbackTasks()
    {
        $task1 = new ProcessCallbackTask(function () {
            touch(__DIR__ . "/task1.tmp");
        });

        $task2 = new ProcessCallbackTask(function () {
            touch(__DIR__ . "/task2.tmp");
        });

        $task3 = new ProcessCallbackTask(function () {
            touch(__DIR__ . "/task3.tmp");
        });

        $this->manager->addTask($task1);
        $this->manager->addTask($task2);
        $this->manager->addTask($task3);

        @unlink(__DIR__ . "/task1.tmp");
        @unlink(__DIR__ . "/task2.tmp");
        @unlink(__DIR__ . "/task3.tmp");

        while ($this->manager->tick()) {
            usleep(250);
        }

        $this->assertFileExists(__DIR__ . "/task1.tmp");
        $this->assertFileExists(__DIR__ . "/task2.tmp");
        $this->assertFileExists(__DIR__ . "/task3.tmp");

        @unlink(__DIR__ . "/task1.tmp");
        @unlink(__DIR__ . "/task2.tmp");
        @unlink(__DIR__ . "/task3.tmp");
    }

    /**
     * @test
     */
    public function handlesRules1()
    {
        // Let's create a task that keeps running for 10 seconds.

        $task1 = new ProcessCallbackTask(function () {
            $ticks = 0;

            while ($ticks++ < 10) {
                sleep(1);
            }
        });

        $this->manager->addTask($task1);

        // Then let's make a rule that says only 1 process can be run at a time.

        $rule1 = new InMemoryRule();
        $rule1->setProcesses(1);

        $this->manager->addRule($rule1);

        $ticks = 0;
        $added = false;

        @unlink(__DIR__ . "/fail-handles-rules.tmp");

        // We'll keep trying to add a task, but it shouldn't be run
        // as there is already 1 task running in the background...

        while ($this->manager->tick() && $ticks++ < 3) {
            sleep(1);

            if (!$added && $ticks > 1) {
                $task2 = new ProcessCallbackTask(function () {
                    touch(__DIR__ . "/fail-handles-rules.tmp");
                });

                $this->manager->addTask($task2);

                $added = true;
            }
        }

        $this->assertFileNotExists(__DIR__ . "/fail-handles-rules.tmp");

        @unlink(__DIR__ . "/fail-handles-rules.tmp");
    }

    /**
     * @test
     */
    public function handlesRules2()
    {
        // Let's create a task that keeps running for 10 seconds.

        $task1 = new TestTask1(function () {
            $ticks = 0;

            while ($ticks++ < 10) {
                sleep(1);
            }
        });

        $this->manager->addTask($task1);

        // Then let's make a rule that says only 1 process can be run at a time.

        $rule1 = new InMemoryRule();
        $rule1->setHandler("AsyncPHP\\Doorman\\Tests\\Manager\\Fixture\\TestHandler1");
        $rule1->setProcesses(1);

        $this->manager->addRule($rule1);

        $ticks = 0;
        $added = false;

        @unlink(__DIR__ . "/fail-handles-rules.tmp");
        @unlink(__DIR__ . "/pass-handles-rules.tmp");

        while ($this->manager->tick() && $ticks++ < 3) {
            sleep(1);

            if (!$added && $ticks > 1) {
                // We'll keep trying to add one task, but it shouldn't be run
                // as there is already 1 task running in the background.

                $task2 = new TestTask1(function () {
                    touch(__DIR__ . "/fail-handles-rules.tmp");
                });

                $this->manager->addTask($task2);

                // We'll add another task (with a different handler) and this should run.

                $task3 = new TestTask2(function () {
                    touch(__DIR__ . "/pass-handles-rules.tmp");
                });

                $this->manager->addTask($task3);

                $added = true;
            }
        }

        $this->assertFileNotExists(__DIR__ . "/fail-handles-rules.tmp");
        $this->assertFileExists(__DIR__ . "/pass-handles-rules.tmp");

        @unlink(__DIR__ . "/fail-handles-rules.tmp");
        @unlink(__DIR__ . "/pass-handles-rules.tmp");
    }

    /**
     * @test
     */
    public function logsOutput()
    {
        $this->manager->setLogPath(__DIR__);

        $task1 = new ProcessCallbackTask(function () {
            print "hello stdout!";
        });

        $task2 = new ProcessCallbackTask(function () {
            throw new Exception("hello stderr!");
        });

        $this->manager->addTask($task1);
        $this->manager->addTask($task2);

        while ($this->manager->tick()) {
            usleep(250);
        }

        $this->assertFileExists(__DIR__ . "/stdout.log");
        $this->assertFileExists(__DIR__ . "/stderr.log");

        @unlink(__DIR__ . "/stdout.log");
        @unlink(__DIR__ . "/stderr.log");
    }

    /**
     * @test
     */
    public function allowsTasksToStopSiblings()
    {
        $task1 = new TestTask3(function () {
            for ($i = 0; $i < 10; $i++) {
                @unlink(__DIR__ . "/allows-tasks-to-stop-siblings.tmp");

                usleep(250);
            }
        });

        $this->manager->addTask($task1);

        @unlink(__DIR__ . "/allows-tasks-to-stop-siblings.tmp");

        $added = false;

        while ($this->manager->tick()) {
            usleep(250);

            if (!$added) {
                $task2 = new TestTask3(function () {
                    touch(__DIR__ . "/allows-tasks-to-stop-siblings.tmp");
                });

                $this->manager->addTask($task2);

                $added = true;
            }
        }

        $this->assertFileExists(__DIR__ . "/allows-tasks-to-stop-siblings.tmp");

        @unlink(__DIR__ . "/allows-tasks-to-stop-siblings.tmp");
    }
}
