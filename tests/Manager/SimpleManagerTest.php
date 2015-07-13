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
        $store = 0;

        $task1 = new SimpleTask(function () use (&$store) {
            $store += 1;
        });

        $task2 = new SimpleTask(function () use (&$store) {
            $store += 2;
        });

        $this->manager->addTask($task1);
        $this->manager->addTask($task2);

        while($this->manager->tick()) {
            usleep(500);
        }

        // tasks should have added 3 to store

        $this->assertEquals(3, $store);
    }
}
