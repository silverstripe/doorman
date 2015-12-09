<?php

namespace AsyncPHP\Doorman\Tests\Task;

use AsyncPHP\Doorman\Task\CallbackTask;
use AsyncPHP\Doorman\Tests\Test;
use AsyncPHP\Doorman\Handler;

/**
 * @covers AsyncPHP\Doorman\Task\CallbackTask
 */
final class CallbackTaskTest extends Test
{
    /**
     * @var CallbackTask
     */
    private $task;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->task = new CallbackTask(function () {
            return "hello world";
        });
    }

    /**
     * @test
     */
    public function taskCanBeSerializedAndUnserialized()
    {
        $serialized = serialize($this->task);

        $task = unserialize($serialized);
        $data = $task->getData();

        $closure = $data["closure"];

        $this->assertEquals("hello world", $closure());
    }

    /**
     * @test
     */
    public function taskReturnsValidHandlerClass()
    {
        $class = $this->task->getHandler();

        $this->assertInstanceOf(Handler::class, new $class());
    }

    /**
     * @test
     */
    public function taskAdheresToRulesAndAllowsSiblings()
    {
        $this->assertFalse($this->task->ignoresRules());
        $this->assertFalse($this->task->stopsSiblings());
    }
}
