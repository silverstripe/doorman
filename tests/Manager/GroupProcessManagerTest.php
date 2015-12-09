<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\GroupProcessManager;
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Manager\GroupProcessManager
 */
final class GroupProcessManagerTest extends Test
{
    /**
     * @var GroupProcessManager
     */
    private $manager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = new GroupProcessManager(
            new ProcessManager()
        );
    }

    /**
     * @test
     */
    public function groupsExecuteInPredictableOrder()
    {
        $this->unlink(__DIR__ . "/task1.temp");
        $this->unlink(__DIR__ . "/task2.temp");
        $this->unlink(__DIR__ . "/task3.temp");
        $this->unlink(__DIR__ . "/task4.temp");

        $task1 = new ProcessCallbackTask(function () {
            (new GroupProcessManagerTest)->dawdle("task1");
        });

        $task2 = new ProcessCallbackTask(function () {
            (new GroupProcessManagerTest)->dawdle("task2");
        });

        $task3 = new ProcessCallbackTask(function () {
            (new GroupProcessManagerTest)->dawdle("task3");
        });

        $task4 = new ProcessCallbackTask(function () {
            (new GroupProcessManagerTest)->dawdle("task4");
        });

        $this->manager->addTask($task1);
        $this->manager->addTaskGroup(array($task2, $task3));
        $this->manager->addTask($task4);

        while ($this->manager->tick()) {
            $exists1 = file_exists(__DIR__ . "/task1.temp");
            $exists2 = file_exists(__DIR__ . "/task2.temp");
            $exists3 = file_exists(__DIR__ . "/task3.temp");
            $exists4 = file_exists(__DIR__ . "/task4.temp");

            if ($exists1 && ($exists2 || $exists3)) {
                $this->fail("task1 should not run at the same time as task2 and/or task3");
            }

            if ($exists4 && ($exists2 || $exists3)) {
                $this->fail("task4 should not run at the same time as task2 and/or task3");
            }

            usleep(25000);
        }
    }

    /**
     * @param string $name
     */
    public function dawdle($name)
    {
        touch(__DIR__ . "/{$name}.temp");

        for ($i = 0; $i < 5; $i++) {
            usleep(25000);
        }

        $this->unlink(__DIR__ . "/{$name}.temp");
    }
}
