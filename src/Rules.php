<?php

namespace AsyncPHP\Doorman;

interface Rules
{
    /**
     * Adds a rule to consider before handling tasks. The same rule cannot be added more than once.
     *
     * @param Rule $rule
     */
    public function addRule(Rule $rule);

    /**
     * Removes a rule.
     *
     * @param Rule $rule
     */
    public function removeRule(Rule $rule);

    /**
     * Instructs a manager whether a task can be handled, given the current rules and load profile.
     *
     * @param Task    $task
     * @param Profile $profile
     *
     * @return bool
     */
    public function canRunTask(Task $task, Profile $profile);
}
