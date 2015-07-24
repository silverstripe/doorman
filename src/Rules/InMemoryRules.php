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
            if ($rule->getProcesses() === null) {
                continue;
            }

            if (($rule->getHandler() === null || $rule->getHandler() === $task->getHandler()) && ($this->hasTooManyProcessesRunning($rule, $profile) || $this->hasTooManySiblingProcessesRunning($rule, $profile))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets all the rules that apply to a task.
     *
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
     * Checks whether there are too many processes running to start a new one.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function hasTooManyProcessesRunning(Rule $rule, Profile $profile)
    {
        return $this->withinConstraints($rule, $profile) && count($profile->getProcesses()) >= $rule->getProcesses();
    }

    /**
     * Checks whether the current profile is within the constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinConstraints(Rule $rule, Profile $profile)
    {
        return $this->withinProcessorConstraints($rule, $profile) || $this->withinMemoryConstraints($rule, $profile);
    }

    /**
     * Checks whether the current profile is within the processor constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinProcessorConstraints(Rule $rule, Profile $profile)
    {
        if ($rule->getMinimumProcessorUsage() !== null && $rule->getMaximumProcessorUsage() !== null) {
            return $profile->getProcessorLoad() >= $rule->getMinimumProcessorUsage() && $profile->getProcessorLoad() <= $rule->getMaximumProcessorUsage();
        }

        return false;
    }

    /**
     * Checks whether the current profile is within the memory constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinMemoryConstraints(Rule $rule, Profile $profile)
    {
        if ($rule->getMinimumMemoryUsage() !== null && $rule->getMaximumMemoryUsage() !== null) {
            return $profile->getMemoryLoad() >= $rule->getMinimumMemoryUsage() && $profile->getMemoryLoad() <= $rule->getMaximumMemoryUsage();
        }

        return false;
    }

    /**
     * Checks whether there are too many sibling processes running to start a new one.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function hasTooManySiblingProcessesRunning(Rule $rule, Profile $profile)
    {
        return $this->withinSiblingConstraints($rule, $profile) && count($profile->getSiblingProcesses()) >= $rule->getProcesses();
    }

    /**
     * Checks whether the profile or sibling processes is within the constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinSiblingConstraints(Rule $rule, Profile $profile)
    {
        return $this->withinSiblingProcessorConstraints($rule, $profile) || $this->withinSiblingMemoryConstraints($rule, $profile);
    }

    /**
     * Checks whether the profile or sibling processes is within the processor constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinSiblingProcessorConstraints(Rule $rule, Profile $profile)
    {
        if ($rule->getMinimumSiblingProcessorUsage() !== null && $rule->getMaximumSiblingProcessorUsage() !== null) {
            return $profile->getSiblingProcessorLoad() >= $rule->getMinimumSiblingProcessorUsage() && $profile->getSiblingProcessorLoad() <= $rule->getMaximumSiblingProcessorUsage();
        }

        return false;
    }

    /**
     * Checks whether the profile or sibling processes is within the memory constraints of a rule.
     *
     * @param Rule    $rule
     * @param Profile $profile
     *
     * @return bool
     */
    protected function withinSiblingMemoryConstraints(Rule $rule, Profile $profile)
    {
        if ($rule->getMinimumSiblingMemoryUsage() !== null && $rule->getMaximumSiblingMemoryUsage() !== null) {
            return $profile->getSiblingMemoryLoad() >= $rule->getMinimumSiblingMemoryUsage() && $profile->getSiblingMemoryLoad() <= $rule->getMaximumSiblingMemoryUsage();
        }

        return false;
    }
}
