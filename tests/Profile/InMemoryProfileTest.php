<?php

namespace AsyncPHP\Doorman\Tests\Profile;

use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class InMemoryProfileTest extends TestCase
{
    /**
     * @var InMemoryProfile
     */
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new InMemoryProfile();
    }

    /**
     * @param string $getter
     * @param string $setter
     * @param mixed $value
     */
    #[DataProvider('provideGettersAndSetters')]
    public function testGettersAndSettersWork($getter, $setter, $value)
    {
        $this->rule->$setter($value);

        $this->assertSame($value, $this->rule->$getter());
    }

    /**
     * @return array
     */
    public static function provideGettersAndSetters()
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
