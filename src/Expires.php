<?php

namespace AsyncPHP\Doorman;

interface Expires
{
    /**
     * Gets the number of seconds until this task or process expires.
     *
     * @return int
     */
    public function getExpiresIn();

    /**
     * Checks whether a task or process should expire. This is called when a manager thinks a task or process should expire.
     *
     * @param int $startedAt
     *
     * @return bool
     */
    public function shouldExpire($startedAt);
}
