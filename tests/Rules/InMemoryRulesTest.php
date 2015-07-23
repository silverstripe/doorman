<?php

namespace AsyncPHP\Doorman\Tests\Task;

use AsyncPHP\Doorman\Profile;
use AsyncPHP\Doorman\Profile\InMemoryProfile;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Rules\InMemoryRules;
use AsyncPHP\Doorman\Task;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;
use AsyncPHP\Doorman\Tests\Test;

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
     * @covers AsyncPHP\Doorman\Rules\InMemoryRules
     */
    public function rulesLimitParallelProcesses()
    {
        $task1 = new ProcessCallbackTask(function () {
            return;
        });

        $task2 = new ProcessCallbackTask(function () {
            return;
        });

        $rule1 = new InMemoryRule();
        $rule1->setProcesses(null);

        $rule2 = new InMemoryRule();
        $rule2->setProcesses(1);

        $rule3 = new InMemoryRule();
        $rule3->setProcesses(2);

        $profile1 = new InMemoryProfile();
        $profile1->setProcesses(array());

        $profile2 = new InMemoryProfile();
        $profile2->setProcesses(array($task1));

        // no rules = all tasks run

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));

        // no processes set on rule = all tasks run

        $this->rules->addRule($rule1);

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));

        // no processes running = all tasks run

        $this->assertTrue($this->rules->canRunTask($task2, $profile1));

        // 1 process running + 1 process allowed = no new tasks run

        $this->rules->removeRule($rule1)->addRule($rule2);

        $this->assertFalse($this->rules->canRunTask($task2, $profile2));

        // 1 processes running + 2 process allowed = all tasks run

        $this->rules->removeRule($rule2)->addRule($rule3);

        $this->assertTrue($this->rules->canRunTask($task2, $profile2));
    }
}
