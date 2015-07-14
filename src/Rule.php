<?php

namespace AsyncPHP\Doorman;

interface Rule
{
    /**
     * @return int
     */
    public function getProcesses();

    /**
     * @return null|string
     */
    public function getHandler();

    /**
     * @return null|float
     */
    public function getMinimumProcessorUsage();

    /**
     * @return null|float
     */
    public function getMaximumProcessorUsage();

    /**
     * @return null|float
     */
    public function getMinimumMemoryUsage();

    /**
     * @return null|float
     */
    public function getMaximumMemoryUsage();

    /**
     * @return null|float
     */
    public function getMinimumSiblingProcessorUsage();

    /**
     * @return null|float
     */
    public function getMaximumSiblingProcessorUsage();

    /**
     * @return null|float
     */
    public function getMinimumSiblingMemoryUsage();

    /**
     * @return null|float
     */
    public function getMaximumSiblingMemoryUsage();
}
