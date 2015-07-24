<?php

namespace AsyncPHP\Doorman\Tests\Profile;

use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Profile\InMemoryProfile
 */
class InMemoryProfileTest extends Test
{
    /**
     * @var InMemoryProfile
     */
    protected $rule;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->rule = new InMemoryProfile();
    }

    /**
     * @test
     *
     * @dataProvider gettersAndSettersProvider
     *
     * @param string $getter
     * @param string $setter
     * @param mixed  $value
     */
    public function gettersAndSettersWork($getter, $setter, $value)
    {
        $this->rule->$setter($value);

        $this->assertSame($value, $this->rule->$getter());
    }

    /**
     * @return array
     */
    public function gettersAndSettersProvider()
    {
        $task = new ProcessCallbackTask(function () {
            return;
        });

        return array(
            array("getProcesses", "setProcesses", array($task)),
            array("getProcessorLoad", "setProcessorLoad", 33.0),
            array("getMemoryLoad", "setMemoryLoad", 33.0),
            array("getSiblingProcesses", "setSiblingProcesses", array($task)),
            array("getSiblingProcessorLoad", "setSiblingProcessorLoad", 33.0),
            array("getSiblingMemoryLoad", "setSiblingMemoryLoad", 33.0),
        );
    }
}
