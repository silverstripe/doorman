<?php

namespace AsyncPHP\Doorman\Tests\Task;

use AsyncPHP\Doorman\Task\CallbackTask;
use PHPUnit\Framework\TestCase;

class CallbackTaskTest extends TestCase
{
    /**
     * @var CallbackTask
     */
    protected $task;

    /**
     * @var callable
     */
    protected $callback;

    public function setUp(): void
    {
        parent::setUp();

        $this->task = new CallbackTask(function () {
            return "hello world";
        });
    }

    public function testTaskCanBeSerializedAndUnserialized()
    {
        $serialized = serialize($this->task);

        $task = unserialize($serialized);
        $data = $task->getData();

        $closure = $data["closure"];

        $this->assertEquals("hello world", $closure());
    }

    public function testTaskReturnsValidHandlerClass()
    {
        $class = $this->task->getHandler();

        $this->assertInstanceOf("AsyncPHP\\Doorman\\Handler", new $class());
    }

    public function testTaskAdheresToRulesAndAllowsSiblings()
    {
        $this->assertFalse($this->task->ignoresRules());
        $this->assertFalse($this->task->stopsSiblings());
    }
}
