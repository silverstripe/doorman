<?php

namespace AsyncPHP\Doorman\Tests\Rule;

use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Rule\InMemoryRule
 */
final class InMemoryRuleTest extends Test
{
    /**
     * @test
     *
     * @dataProvider dataProvider
     *
     * @param string $option
     * @param string $getter
     * @param mixed $value
     */
    public function optionsCorrectlyResolved($option, $getter, $value)
    {
        $rule = new InMemoryRule([$option => $value]);

        $this->assertSame($value, $rule->$getter());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [Rule::LIMIT, "getLimit", 3],
            [Rule::HANDLER, "getHandler", "Three"],
            [Rule::MINIMUM_GLOBAL_PROCESSOR_USAGE, "getMinimumGlobalProcessorUsage", 33],
            [Rule::MAXIMUM_GLOBAL_PROCESSOR_USAGE, "getMaximumGlobalProcessorUsage", 33],
            [Rule::MINIMUM_GLOBAL_MEMORY_USAGE, "getMinimumGlobalMemoryUsage", 33],
            [Rule::MAXIMUM_GLOBAL_MEMORY_USAGE, "getMaximumGlobalMemoryUsage", 33],
            [Rule::MINIMUM_SIBLING_PROCESSOR_USAGE, "getMinimumSiblingProcessorUsage", 33],
            [Rule::MAXIMUM_SIBLING_PROCESSOR_USAGE, "getMaximumSiblingProcessorUsage", 33],
            [Rule::MINIMUM_SIBLING_MEMORY_USAGE, "getMinimumSiblingMemoryUsage", 33],
            [Rule::MAXIMUM_SIBLING_MEMORY_USAGE, "getMaximumSiblingMemoryUsage", 33],
        ];
    }
}
