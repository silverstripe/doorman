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

    /**
     * Gets the CPU load for this process.
     *
     * @return float
     */
    public function getCpu();

    /**
     * Sets the CPU load for this process.
     *
     * @param float $cpu
     */
    public function setCpu($cpu);

    /**
     * Gets the memory load for this process.
     *
     * @return float
     */
    public function getMemory();

    /**
     * Sets the memory load for this process.
     *
     * @param float $memory
     */
    public function setMemory($memory);
}
