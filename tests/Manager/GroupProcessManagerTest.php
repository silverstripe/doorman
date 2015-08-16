<?php

namespace AsyncPHP\Doorman\Tests\Manager;

use AsyncPHP\Doorman\Manager\GroupProcessManager;
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Manager\GroupProcessManager
 */
class GroupProcessManagerTest extends Test
{
    /**
     * @var GroupProcessManager
     */
    protected $manager;

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
        $this->unlink("task1");
        $this->unlink("task2");
        $this->unlink("task3");
        $this->unlink("task4");

        $task1 = new ProcessCallbackTask(function () {
            GroupProcessManagerTest::dawdle("task1");
        });

        $task2 = new ProcessCallbackTask(function () {
            GroupProcessManagerTest::dawdle("task2");
        });

        $task3 = new ProcessCallbackTask(function () {
            GroupProcessManagerTest::dawdle("task3");
        });

        $task4 = new ProcessCallbackTask(function () {
            GroupProcessManagerTest::dawdle("task4");
        });

        $this->manager->addTask($task1);
        $this->manager->addTaskGroup(array($task2, $task3));
        $this->manager->addTask($task4);

        while ($this->manager->tick()) {
            $exists1 = $this->exists("task1");
            $exists2 = $this->exists("task2");
            $exists3 = $this->exists("task3");
            $exists4 = $this->exists("task4");

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
    protected function unlink($name)
    {
        if ($this->exists($name)) {
            unlink(__DIR__ . "/{$name}.temp");
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function exists($name)
    {
        return file_exists(__DIR__ . "/{$name}.temp");
    }

    /**
     * @param string $name
     */
    public static function dawdle($name)
    {
        touch(__DIR__ . "/{$name}.temp");

        for ($i = 0; $i < 5; $i++) {
            usleep(25000);
        }

        unlink(__DIR__ . "/{$name}.temp");
    }
}
