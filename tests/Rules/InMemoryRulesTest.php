<?php

namespace AsyncPHP\Doorman\Tests\Rules;

use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Rules\InMemoryRules
 */
class InMemoryRulesTest extends Test
{
    /**
     * @var InMemoryRules
     */
    protected $rules;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->rules = new InMemoryRules();
    }

    /**
     * @test
     */
    public function rulesLimitParallelProcesses()
    {
        $task1 = new ProcessCallbackTask(function () {
            return;
        });

        $task2 = new ProcessCallbackTask(function () {
            return;
        });

        $profile1 = new InMemoryProfile();
        $profile1->setProcesses(array());

        $profile2 = new InMemoryProfile();
        $profile2->setProcesses(array($task1));

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));

        $rule1 = new InMemoryRule();
        $rule1->setProcesses(null);
        $rule1->setMinimumProcessorUsage(0);
        $rule1->setMaximumProcessorUsage(100);

        $this->rules->addRule($rule1);

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));

        $this->assertTrue($this->rules->canRunTask($task2, $profile1));

        $rule2 = new InMemoryRule();
        $rule2->setProcesses(1);
        $rule2->setMinimumProcessorUsage(0);
        $rule2->setMaximumProcessorUsage(100);

        $this->rules->removeRule($rule1)->addRule($rule2);

        $this->assertFalse($this->rules->canRunTask($task2, $profile2));

        $rule3 = new InMemoryRule();
        $rule3->setProcesses(2);
        $rule3->setMinimumProcessorUsage(0);
        $rule3->setMaximumProcessorUsage(100);

        $this->rules->removeRule($rule2)->addRule($rule3);

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));
    }

    /**
     * @test
     */
    public function rulesLimitProcessorAndMemoryUsage()
    {
        $task1 = new ProcessCallbackTask(function () {
            return;
        });

        $task2 = new ProcessCallbackTask(function () {
            return;
        });

        $rule1 = new InMemoryRule();
        $rule1->setProcesses(1);
        $rule1->setMinimumProcessorUsage(50);
        $rule1->setMaximumProcessorUsage(100);

        $profile1 = new InMemoryProfile();
        $profile1->setProcesses(array($task1));
        $profile1->setProcessorLoad(75);

        $this->assertFalse($this->rules->addRule($rule1)->canRunTask($task2, $profile1));

        $rule2 = new InMemoryRule();
        $rule2->setProcesses(1);
        $rule2->setMinimumMemoryUsage(50);
        $rule2->setMaximumMemoryUsage(100);

        $profile2 = new InMemoryProfile();
        $profile2->setProcesses(array($task1));
        $profile2->setMemoryLoad(75);

        $this->assertFalse($this->rules->removeRule($rule1)->addRule($rule2)->canRunTask($task2, $profile2));

        $rule3 = new InMemoryRule();
        $rule3->setProcesses(1);
        $rule3->setMinimumSiblingProcessorUsage(50);
        $rule3->setMaximumSiblingProcessorUsage(100);

        $profile3 = new InMemoryProfile();
        $profile3->setSiblingProcesses(array($task1));
        $profile3->setSiblingProcessorLoad(75);

        $this->assertFalse($this->rules->removeRule($rule2)->addRule($rule3)->canRunTask($task2, $profile3));

        $rule4 = new InMemoryRule();
        $rule4->setProcesses(1);
        $rule4->setMinimumSiblingMemoryUsage(50);
        $rule4->setMaximumSiblingMemoryUsage(100);

        $profile4 = new InMemoryProfile();
        $profile4->setSiblingProcesses(array($task1));
        $profile4->setSiblingMemoryLoad(75);

        $this->assertFalse($this->rules->removeRule($rule3)->addRule($rule4)->canRunTask($task2, $profile4));
    }
}
