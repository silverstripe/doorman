<?php

namespace AsyncPHP\Doorman\Rules;

use AsyncPHP\Doorman\Profile;
use AsyncPHP\Doorman\Rule;
use AsyncPHP\Doorman\Rules;
use AsyncPHP\Doorman\Task;

class InMemoryRules implements Rules
{
    /**
     * @var Rule[]
     */
    protected $rules = array();

    /**
     * @inheritdoc
     *
     * @param Rule $rule
     *
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        $hash = spl_object_hash($rule);

        $this->rules[$hash] = $rule;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param Rule $rule
     *
     * @return $this
     */
    public function removeRule(Rule $rule)
    {
        $hash = spl_object_hash($rule);

        if (isset($this->rules[$hash])) {
            unset($this->rules[$hash]);
        }

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param Task    $task
     * @param Profile $profile
     *
     * @return bool
     */
    public function canRunTask(Task $task, Profile $profile)
    {
        $rules = $this->getRulesForTask($task);

        if (count($rules) > 0) {
            foreach ($rules as $rule) {
                if ($rule->getHandler() === null && $this->withinConstraints($rule, $profile) && $this->withinSiblingConstraints($rule, $profile) && count($profile->getProcesses()) >= $rule->getProcesses()) {
                    return false;
                }

                if ($rule->getHandler() === $task->getHandler() && $this->withinConstraints($rule, $profile) && $this->withinSiblingConstraints($rule, $profile) && count($profile->getSiblingProcesses()) >= $rule->getProcesses()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param Task $task
     *
     * @return Rule[]
     */
    protected function getRulesForTask(Task $task)
    {
        return array_filter($this->rules, function (Rule $rule) use ($task) {
            return $rule->getHandler() === null || $rule->getHandler() === $task->getHandler();
        });
    }

    /**
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinConstraints(Rule $rule, Profile $profile)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumProcessorUsage()) {
            $minimumProcessor = $rule->getMinimumProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMinimumProcessorUsage()) {
            $maximumProcessor = $rule->getMaximumProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumMemoryUsage()) {
            $minimumMemory = $rule->getMinimumMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMinimumMemoryUsage()) {
            $maximumMemory = $rule->getMaximumMemoryUsage();
        }

        $processor = $profile->getProcessorLoad();

        $memory = $profile->getMemoryLoad();

        return $processor >= $minimumProcessor && $processor <= $maximumProcessor && $memory >= $minimumMemory && $memory <= $maximumMemory;
    }

    /**
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinSiblingConstraints(Rule $rule, Profile $profile)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumSiblingProcessorUsage()) {
            $minimumProcessor = $rule->getMinimumSiblingProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMinimumSiblingProcessorUsage()) {
            $maximumProcessor = $rule->getMaximumSiblingProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumSiblingMemoryUsage()) {
            $minimumMemory = $rule->getMinimumSiblingMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMinimumSiblingMemoryUsage()) {
            $maximumMemory = $rule->getMaximumSiblingMemoryUsage();
        }

        $processor = $profile->getSiblingProcessorLoad();

        $memory = $profile->getSiblingMemoryLoad();

        return $processor >= $minimumProcessor && $processor <= $maximumProcessor && $memory >= $minimumMemory && $memory <= $maximumMemory;
    }
}
