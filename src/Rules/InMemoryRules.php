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

        if (count($rules) === 0) {
            return true;
        }

        foreach ($rules as $rule) {
            $withinConstraints = $this->withinConstraints($rule, $profile) or  $this->withinSiblingConstraints($rule, $profile);

            if ($rule->getProcesses() === null) {
                continue;
            }

            if ($rule->getHandler() === null and $withinConstraints and count($profile->getProcesses()) >= $rule->getProcesses()) {
                return false;
            }

            if ($rule->getHandler() === $task->getHandler() and $withinConstraints and count($profile->getSiblingProcesses()) >= $rule->getProcesses()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @todo description
     *
     * @param Task $task
     *
     * @return Rule[]
     */
    protected function getRulesForTask(Task $task)
    {
        return array_filter($this->rules, function (Rule $rule) use ($task) {
            return $rule->getHandler() === null or $rule->getHandler() === $task->getHandler();
        });
    }

    /**
     * @todo description
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinConstraints(Rule $rule, Profile $profile)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumProcessorUsage() !== null) {
            $minimumProcessor = $rule->getMinimumProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMaximumProcessorUsage() !== null) {
            $maximumProcessor = $rule->getMaximumProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumMemoryUsage() !== null) {
            $minimumMemory = $rule->getMinimumMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMaximumMemoryUsage() !== null) {
            $maximumMemory = $rule->getMaximumMemoryUsage();
        }

        $processor = $profile->getProcessorLoad();

        $memory = $profile->getMemoryLoad();

        return $processor >= $minimumProcessor and $processor <= $maximumProcessor and $memory >= $minimumMemory and $memory <= $maximumMemory;
    }

    /**
     * @todo description
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinSiblingConstraints(Rule $rule, Profile $profile)
    {
        $minimumProcessor = 0;

        if ($rule->getMinimumSiblingProcessorUsage() !== null) {
            $minimumProcessor = $rule->getMinimumSiblingProcessorUsage();
        }

        $maximumProcessor = 100;

        if ($rule->getMaximumSiblingProcessorUsage() !== null) {
            $maximumProcessor = $rule->getMaximumSiblingProcessorUsage();
        }
        $minimumMemory = 0;

        if ($rule->getMinimumSiblingMemoryUsage() !== null) {
            $minimumMemory = $rule->getMinimumSiblingMemoryUsage();
        }

        $maximumMemory = 100;

        if ($rule->getMaximumSiblingMemoryUsage() !== null) {
            $maximumMemory = $rule->getMaximumSiblingMemoryUsage();
        }

        $processor = $profile->getSiblingProcessorLoad();

        $memory = $profile->getSiblingMemoryLoad();

        return $processor >= $minimumProcessor and $processor <= $maximumProcessor and $memory >= $minimumMemory and $memory <= $maximumMemory;
    }
}
