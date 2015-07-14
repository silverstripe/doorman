<?php

namespace AsyncPHP\Doorman\Tests\Handler;

use AsyncPHP\Doorman\Handler\CallbackHandler;
use AsyncPHP\Doorman\Task\CallbackTask;
use AsyncPHP\Doorman\Tests\Test;

class CallbackHandlerTest extends Test
{
    /**
     * @var CallbackHandler
     */
    protected $handler;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->handler = new CallbackHandler();
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
    public function handlesCallbackTasks()
    {
        $store = 0;

        $task1 = new CallbackTask(function () use (&$store) {
            $store += 1;
        });

        $task2 = new CallbackTask(function () use (&$store) {
            $store += 2;
        });

        $this->handler->handle($task1);
        $this->handler->handle($task2);

        // tasks should have added 3 to store

        $this->assertEquals(3, $store);
    }
}
