<?php

namespace AsyncPHP\Doorman\Tests\Rule;

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
     * @dataProvider ruleProvider
     *
     * @param array $parameters
     * @param string $getter
     * @param mixed $value
     */
    public function gettersWork(array $parameters, $getter, $value)
    {
        $rule = new InMemoryRule($parameters);

        $this->assertSame($value, $rule->$getter());
    }

    /**
     * @return array
     */
    public function ruleProvider()
    {
        return [
            [["processes" => 3], "getProcesses", 3],
            [["handler" => "Three"], "getHandler", "Three"],
            [["handlers" => ["processor" => ["minimum" => 33.0]]], "getMinimumProcessorUsage", 33.0],
            [["handlers" => ["processor" => ["maximum" => 33.0]]], "getMaximumProcessorUsage", 33.0],
            [["handlers" => ["memory" => ["minimum" => 33.0]]], "getMinimumMemoryUsage", 33.0],
            [["handlers" => ["memory" => ["maximum" => 33.0]]], "getMaximumMemoryUsage", 33.0],
            [["siblings" => ["processor" => ["minimum" => 33.0]]], "getMinimumSiblingProcessorUsage", 33.0],
            [["siblings" => ["processor" => ["maximum" => 33.0]]], "getMaximumSiblingProcessorUsage", 33.0],
            [["siblings" => ["memory" => ["minimum" => 33.0]]], "getMinimumSiblingMemoryUsage", 33.0],
            [["siblings" => ["memory" => ["maximum" => 33.0]]], "getMaximumSiblingMemoryUsage", 33.0],
        ];
    }
}
