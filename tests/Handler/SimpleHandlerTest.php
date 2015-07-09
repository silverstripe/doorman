<?php

namespace AsyncPHP\Doorman\Tests\Handler;

use AsyncPHP\Doorman\Handler\SimpleHandler;
use AsyncPHP\Doorman\Task\SimpleTask;
use AsyncPHP\Doorman\Tests\Test;

class SimpleHandlerTest extends Test
{
    /**
     * @var SimpleHandler
     */
    protected $handler;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->handler = new SimpleHandler();
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->handler = null;

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

        $this->handler->handle($task1);
        $this->handler->handle($task2);

        // tasks should have added 3 to store

        $this->assertEquals(3, $store);
    }
}
