<?php

namespace AsyncPHP\Doorman\Tests\Rule;

use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Rule\InMemoryRule
 */
class InMemoryRuleTest extends Test
{
    /**
     * @var InMemoryRule
     */
    protected $rule;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->rule = new InMemoryRule();
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
