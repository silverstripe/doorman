<?php

# This file is generated, changes you make will be lost.
# Make your changes in /Users/assertchris/Source/asyncphp/doorman/src/Rule/InMemoryRule.pre instead.

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;

final class InMemoryRule implements Rule
{
    /**
     * @var null|int
     */
    private $processes;

    public function getProcesses()
    {
        return $this->processes;
    }

    public function setProcesses($value)
    {
        $this->processes = $value;
    }

    /**
     * @var null|string
     */
    private $handler;

    public function getHandler()
    {
        return $this->handler;
    }

    public function setHandler($value)
    {
        $this->handler = $value;
    }

    /**
     * @var null|float
     */
    private $minimumProcessorUsage;

    public function getMinimumProcessorUsage()
    {
        return $this->minimumProcessorUsage;
    }

    public function setMinimumProcessorUsage($value)
    {
        $this->minimumProcessorUsage = $value;
    }

    /**
     * @var null|float
     */
    private $maximumProcessorUsage;

    public function getMaximumProcessorUsage()
    {
        return $this->maximumProcessorUsage;
    }

    public function setMaximumProcessorUsage($value)
    {
        $this->maximumProcessorUsage = $value;
    }

    /**
     * @var null|float
     */
    private $minimumMemoryUsage;

    public function getMinimumMemoryUsage()
    {
        return $this->minimumMemoryUsage;
    }

    public function setMinimumMemoryUsage($value)
    {
        $this->minimumMemoryUsage = $value;
    }

    /**
     * @var null|float
     */
    private $maximumMemoryUsage;

    public function getMaximumMemoryUsage()
    {
        return $this->maximumMemoryUsage;
    }

    public function setMaximumMemoryUsage($value)
    {
        $this->maximumMemoryUsage = $value;
    }

    /**
     * @var null|float
     */
    private $minimumSiblingProcessorUsage;

    public function getMinimumSiblingProcessorUsage()
    {
        return $this->minimumSiblingProcessorUsage;
    }

    public function setMinimumSiblingProcessorUsage($value)
    {
        $this->minimumSiblingProcessorUsage = $value;
    }

    /**
     * @var null|float
     */
    private $maximumSiblingProcessorUsage;

    public function getMaximumSiblingProcessorUsage()
    {
        return $this->maximumSiblingProcessorUsage;
    }

    public function setMaximumSiblingProcessorUsage($value)
    {
        $this->maximumSiblingProcessorUsage = $value;
    }

    /**
     * @var null|float
     */
    private $minimumSiblingMemoryUsage;

    public function getMinimumSiblingMemoryUsage()
    {
        return $this->minimumSiblingMemoryUsage;
    }

    public function setMinimumSiblingMemoryUsage($value)
    {
        $this->minimumSiblingMemoryUsage = $value;
    }

    /**
     * @var null|float
     */
    private $maximumSiblingMemoryUsage;

    public function getMaximumSiblingMemoryUsage()
    {
        return $this->maximumSiblingMemoryUsage;
    }

    public function setMaximumSiblingMemoryUsage($value)
    {
        $this->maximumSiblingMemoryUsage = $value;
    }
}
