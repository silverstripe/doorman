<?php

namespace AsyncPHP\Doorman\Tests\TAsk;

use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Task\ProcessCallbackTask
 */
class ProcessCallbackTaskTest extends Test
{
    /**
     * @var ProcessCallbackTask
     */
    protected $task;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->task = new ProcessCallbackTask(function () {
            return;
        });
    }

    /**
     * @test
     */
    public function gettersAndSettersWork()
    {
        $this->task->setId(3);

        $this->assertEquals(3, $this->task->getId());

        $this->assertEquals(-1, $this->task->getExpiresIn());

        $this->assertFalse($this->task->hasExpired());

        $this->assertTrue($this->task->shouldExpire(time()));

        $this->assertTrue($this->task->hasExpired());
    }
}
