<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\SynchronousManager;
use AsyncPHP\Doorman\Task\CallbackTask;
use AsyncPHP\Doorman\Tests\Test;

class SynchronousManagerTest extends Test
{
    /**
     * @var SynchronousManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = new SynchronousManager();
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
    public function handlesCallbackTasks()
    {
        $task1 = new CallbackTask(function () {
            touch(__DIR__ . "/task1.tmp");
        });

        $task2 = new CallbackTask(function () {
            touch(__DIR__ . "/task2.tmp");
        });

        $this->manager->addTask($task1);
        $this->manager->addTask($task2);

        @unlink(__DIR__ . "/task1.tmp");
        @unlink(__DIR__ . "/task2.tmp");

        while ($this->manager->tick()) {
            usleep(250);
        }

        $this->assertFileExists(__DIR__ . "/task1.tmp");
        $this->assertFileExists(__DIR__ . "/task2.tmp");

        @unlink(__DIR__ . "/task1.tmp");
        @unlink(__DIR__ . "/task2.tmp");
    }
}
