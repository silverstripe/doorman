<?php

namespace AsyncPHP\Doorman\Tests\Rule;

use AsyncPHP\Doorman\Rule\InMemoryRule;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class InMemoryRuleTest extends TestCase
{
    /**
     * @var InMemoryRule
     */
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new InMemoryRule();
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
        return array(
            array("getProcesses", "setProcesses", 3),
            array("getHandler", "setHandler", "Three"),
            array("getMinimumProcessorUsage", "setMinimumProcessorUsage", 33.0),
            array("getMaximumProcessorUsage", "setMaximumProcessorUsage", 33.0),
            array("getMinimumMemoryUsage", "setMinimumMemoryUsage", 33.0),
            array("getMaximumMemoryUsage", "setMaximumMemoryUsage", 33.0),
            array("getMinimumSiblingProcessorUsage", "setMinimumSiblingProcessorUsage", 33.0),
            array("getMaximumSiblingProcessorUsage", "setMaximumSiblingProcessorUsage", 33.0),
            array("getMinimumSiblingMemoryUsage", "setMinimumSiblingMemoryUsage", 33.0),
            array("getMaximumSiblingMemoryUsage", "setMaximumSiblingMemoryUsage", 33.0),
        );
    }
}
