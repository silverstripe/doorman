<?php

namespace AsyncPHP\Doorman\Tests\Handler;

use AsyncPHP\Doorman\Handler\CallbackHandler;
use AsyncPHP\Doorman\Task\CallbackTask;
use PHPUnit\Framework\TestCase;

class CallbackHandlerTest extends TestCase
{
    /**
     * @var CallbackHandler
     */
    protected $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->handler = new CallbackHandler();
    }

    public function testHandlesCallbackTasks()
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

        $this->assertEquals(3, $store);
    }
}
