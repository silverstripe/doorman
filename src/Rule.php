<?php

namespace AsyncPHP\Doorman;

interface Rule
{
    /**
     * Gets the number of processes a manager is allowed to run at once, following this rule.
     *
     * @return int
     */
    public function getProcesses();

    /**
     * Gets the name of the handler which defines sibling processes.
     *
     * @return null|string
     */
    public function getHandler();

    /**
     * Gets the required minimum processor usage of all running processes.
     *
     * @return null|float
     */
    public function getMinimumProcessorUsage();

    /**
     * Gets the required maximum processor usage of all running processes.
     *
     * @return null|float
     */
    public function getMaximumProcessorUsage();

    /**
     * Gets the required minimum memory usage of all running processes.
     *
     * @return null|float
     */
    public function getMinimumMemoryUsage();

    /**
     * Gets the required maximum memory usage of all running processes.
     *
     * @return null|float
     */
    public function getMaximumMemoryUsage();

    /**
     * Gets the required minimum processor usage of processes using the same handler.
     *
     * @return null|float
     */
    public function getMinimumSiblingProcessorUsage();

    /**
     * Gets the required maximum processor usage of processes using the same handler.
     *
     * @return null|float
     */
    public function getMaximumSiblingProcessorUsage();

    /**
     * Gets the required minimum memory usage of processes using the same handler.
     *
     * @return null|float
     */
    public function getMinimumSiblingMemoryUsage();

    /**
     * Gets the required maximum memory usage of processes using the same handler.
     *
     * @return null|float
     */
    public function getMaximumSiblingMemoryUsage();
}
