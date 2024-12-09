<?php

namespace AsyncPHP\Doorman\Tests\TAsk;

use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use PHPUnit\Framework\TestCase;

class ProcessCallbackTaskTest extends TestCase
{
    /**
     * @var ProcessCallbackTask
     */
    protected $task;

    public function setUp(): void
    {
        parent::setUp();

        $this->task = new ProcessCallbackTask(function () {
            return;
        });
    }

    public function testGettersAndSettersWork()
    {
        $this->task->setId(3);

        $this->assertEquals(3, $this->task->getId());

        $this->assertEquals(-1, $this->task->getExpiresIn());

        $this->assertFalse($this->task->hasExpired());

        $this->assertTrue($this->task->shouldExpire(time()));

        $this->assertTrue($this->task->hasExpired());
    }
}
