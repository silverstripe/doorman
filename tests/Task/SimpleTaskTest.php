<?php

namespace AsyncPHP\Doorman\Tests\Task;

use AsyncPHP\Doorman\Task\SimpleTask;
use AsyncPHP\Doorman\Tests\Test;

class SimpleTaskTest extends Test
{
    /**
     * @var SimpleTask
     */
    protected $task;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->task = new SimpleTask(function () {
            return "hello world";
        });
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->task = null;

        parent::tearDown();
    }

    /**
     * @test
     */
    public function taskCanBeSerializedAndUnserialized()
    {
        // tasks should be able to serialize

        $serialized = serialize($this->task);

        // and, when unserialized, function as normal

        $task = unserialize($serialized);
        $data = $task->getData();

        $callback = $data["callback"];

        $this->assertEquals("hello world", $callback());
    }
}
