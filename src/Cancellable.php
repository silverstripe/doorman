<?php

namespace AsyncPHP\Doorman;

interface Cancellable
{
    /**
     * Checks whether this task is cancelled.
     *
     * @return bool
     */
    public function isCancelled();
}
