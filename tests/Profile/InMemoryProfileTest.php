<?php

namespace AsyncPHP\Doorman\Tests\Profile;

use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Profile\InMemoryProfile
 */
final class InMemoryProfileTest extends Test
{
    /**
     * @var InMemoryProfile
     */
    private $rule;

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
     * @param mixed $value
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

        return [
            ["getProcesses", "setProcesses", [$task]],
            ["getProcessorLoad", "setProcessorLoad", 33.0],
            ["getMemoryLoad", "setMemoryLoad", 33.0],
            ["getSiblingProcesses", "setSiblingProcesses", [$task]],
            ["getSiblingProcessorLoad", "setSiblingProcessorLoad", 33.0],
            ["getSiblingMemoryLoad", "setSiblingMemoryLoad", 33.0],
        ];
    }
}
