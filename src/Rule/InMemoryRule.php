<?php

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;

class InMemoryRule implements Rule
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
