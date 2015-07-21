<?php

namespace AsyncPHP\Doorman;

interface Rules
{
    /**
     * @todo description
     *
     * @param Rule $rule
     */
    public function addRule(Rule $rule);

    /**
     * @todo description
     *
     * @param Rule $rule
     */
    public function removeRule(Rule $rule);

    /**
     * @todo description
     *
     * @param Task    $task
     * @param Profile $profile
     *
     * @return bool
     */
    public function canRunTask(Task $task, Profile $profile);
}
