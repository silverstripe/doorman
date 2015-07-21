<?php

namespace AsyncPHP\Doorman;

interface Rule
{
    /**
     * @todo description
     *
     * @return int
     */
    public function getProcesses();

    /**
     * @todo description
     *
     * @return null|string
     */
    public function getHandler();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMinimumProcessorUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMaximumProcessorUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMinimumMemoryUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMaximumMemoryUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMinimumSiblingProcessorUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMaximumSiblingProcessorUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMinimumSiblingMemoryUsage();

    /**
     * @todo description
     *
     * @return null|float
     */
    public function getMaximumSiblingMemoryUsage();
}
