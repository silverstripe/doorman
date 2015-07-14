<?php

namespace AsyncPHP\Doorman;

interface Process
{
    /**
     * Gets the ID for this process.
     *
     * @return null|int
     */
    public function getId();

    /**
     * Sets the ID for this process.
     *
     * @param int $pid
     */
    public function setId($pid);
}
