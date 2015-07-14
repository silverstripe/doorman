<?php

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;

class SimpleRule implements Rule
{
    /**
     * @var null|int
     */
    protected $processes;

    /**
     * @var null|string
     */
    protected $handler;

    /**
     * @var null|float
     */
    protected $minimumProcessorUsage;

    /**
     * @var null|float
     */
    protected $maximumProcessorUsage;

    /**
     * @var null|float
     */
    protected $minimumMemoryUsage;

    /**
     * @var null|float
     */
    protected $maximumMemoryUsage;

    /**
     * @var null|float
     */
    protected $minimumSiblingProcessorUsage;

    /**
     * @var null|float
     */
    protected $maximumSiblingProcessorUsage;

    /**
     * @var null|float
     */
    protected $minimumSiblingMemoryUsage;

    /**
     * @var null|float
     */
    protected $maximumSiblingMemoryUsage;

    /**
     * @inheritdoc
     *
     * @return int|null
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * Sets the number of processes a manager is allowed to run at once, following this rule.
     *
     * @param int|null $processes
     *
     * @return $this
     */
    public function setProcesses($processes)
    {
        $this->processes = $processes;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Sets the name of the handler which defines sibling processes.
     *
     * @param null|string $handler
     *
     * @return $this
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumProcessorUsage()
    {
        return $this->minimumProcessorUsage;
    }

    /**
     * Sets the required minimum processor usage of all running processes.
     *
     * @param null|float $minimumProcessorUsage
     *
     * @return $this
     */
    public function setMinimumProcessorUsage($minimumProcessorUsage)
    {
        $this->minimumProcessorUsage = $minimumProcessorUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumProcessorUsage()
    {
        return $this->maximumProcessorUsage;
    }

    /**
     * Sets the required maximum processor usage of all running processes.
     *
     * @param null|float $maximumProcessorUsage
     *
     * @return $this
     */
    public function setMaximumProcessorUsage($maximumProcessorUsage)
    {
        $this->maximumProcessorUsage = $maximumProcessorUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumMemoryUsage()
    {
        return $this->minimumMemoryUsage;
    }

    /**
     * Sets the required minimum memory usage of all running processes.
     *
     * @param null|float $minimumMemoryUsage
     *
     * @return $this
     */
    public function setMinimumMemoryUsage($minimumMemoryUsage)
    {
        $this->minimumMemoryUsage = $minimumMemoryUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumMemoryUsage()
    {
        return $this->maximumMemoryUsage;
    }

    /**
     * Sets the required maximum memory usage of all running processes.
     *
     * @param null|float $maximumMemoryUsage
     *
     * @return $this
     */
    public function setMaximumMemoryUsage($maximumMemoryUsage)
    {
        $this->maximumMemoryUsage = $maximumMemoryUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumSiblingProcessorUsage()
    {
        return $this->minimumSiblingProcessorUsage;
    }

    /**
     * Sets the required minimum processor usage of processes using the same handler.
     *
     * @param null|float $minimumSiblingProcessorUsage
     *
     * @return $this
     */
    public function setMinimumSiblingProcessorUsage($minimumSiblingProcessorUsage)
    {
        $this->minimumSiblingProcessorUsage = $minimumSiblingProcessorUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumSiblingProcessorUsage()
    {
        return $this->maximumSiblingProcessorUsage;
    }

    /**
     * Sets the required maximum processor usage of processes using the same handler.
     *
     * @param null|float $maximumSiblingProcessorUsage
     *
     * @return $this
     */
    public function setMaximumSiblingProcessorUsage($maximumSiblingProcessorUsage)
    {
        $this->maximumSiblingProcessorUsage = $maximumSiblingProcessorUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumSiblingMemoryUsage()
    {
        return $this->minimumSiblingMemoryUsage;
    }

    /**
     * Sets the required minimum memory usage of processes using the same handler.
     *
     * @param null|float $minimumSiblingMemoryUsage
     *
     * @return $this
     */
    public function setMinimumSiblingMemoryUsage($minimumSiblingMemoryUsage)
    {
        $this->minimumSiblingMemoryUsage = $minimumSiblingMemoryUsage;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumSiblingMemoryUsage()
    {
        return $this->maximumSiblingMemoryUsage;
    }

    /**
     * Sets the required maximum memory usage of processes using the same handler.
     *
     * @param null|float $maximumSiblingMemoryUsage
     *
     * @return $this
     */
    public function setMaximumSiblingMemoryUsage($maximumSiblingMemoryUsage)
    {
        $this->maximumSiblingMemoryUsage = $maximumSiblingMemoryUsage;

        return $this;
    }
}