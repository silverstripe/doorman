<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\SynchronousManager;
use AsyncPHP\Doorman\Task\CallbackTask;
use PHPUnit\Framework\TestCase;

class SynchronousManagerTest extends TestCase
{
    /**
     * @var SynchronousManager
     */
    protected $manager;

    public function setUp(): void
    {
        parent::setUp();

        $this->manager = new SynchronousManager();
    }

    public function testHandlesCallbackTasks()
    {
        $task1 = new CallbackTask(function () {
            touch(__DIR__ . "/task1.tmp");
        });

        $task2 = new CallbackTask(function () {
            touch(__DIR__ . "/task2.tmp");
        });

        $this->manager->addTask($task1);
        $this->manager->addTask($task2);

        $this->unlink(__DIR__ . "/task1.tmp");
        $this->unlink(__DIR__ . "/task2.tmp");

        while ($this->manager->tick()) {
            usleep(250);
        }

        $this->assertFileExists(__DIR__ . "/task1.tmp");
        $this->assertFileExists(__DIR__ . "/task2.tmp");

        $this->unlink(__DIR__ . "/task1.tmp");
        $this->unlink(__DIR__ . "/task2.tmp");
    }

    private function unlink($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
