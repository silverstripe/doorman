<?php

namespace AsyncPHP\Doorman;

interface Rules
{
    /**
     * @param Rule $rule
     */
    public function addRule(Rule $rule);

    /**
     * @param Rule $rule
     */
    public function removeRule(Rule $rule);

    /**
     * @param Task    $task
     * @param Profile $profile
     *
     * @return bool
     */
    public function canRunTask(Task $task, Profile $profile);
}
