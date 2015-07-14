<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\SimpleManager;
use AsyncPHP\Doorman\Task\SimpleTask;
use AsyncPHP\Doorman\Tests\Test;

class SimpleManagerTest extends Test
{
    /**
     * @var SimpleManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = new SimpleManager();
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
    public function handlesSimpleTasks()
    {
        $task1 = new SimpleTask(function () {
            touch(__DIR__ . "/task1.tmp");
        });

        $task2 = new SimpleTask(function () {
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
