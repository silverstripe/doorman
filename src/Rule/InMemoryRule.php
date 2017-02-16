<?php

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;

final class InMemoryRule implements Rule
{
    /**
     * @var null|int
     */
    private $processes;

    /**
     * @var null|string
     */
    private $handler;

    /**
     * @var null|float
     */
    private $minimumProcessorUsage;

    /**
     * @var null|float
     */
    private $maximumProcessorUsage;

    /**
     * @var null|float
     */
    private $minimumMemoryUsage;

    /**
     * @var null|float
     */
    private $maximumMemoryUsage;

    /**
     * @var null|float
     */
    private $minimumSiblingProcessorUsage;

    /**
     * @var null|float
     */
    private $maximumSiblingProcessorUsage;

    /**
     * @var null|float
     */
    private $minimumSiblingMemoryUsage;

    /**
     * @var null|float
     */
    private $maximumSiblingMemoryUsage;

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @param int $processes
     *
     * @return $this
     */
    public function setProcesses(int $processes)
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
     * @param string $handler
     *
     * @return $this
     */
    public function setHandler(string $handler)
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
     * @param float $minimumProcessorUsage
     *
     * @return $this
     */
    public function setMinimumProcessorUsage(float $minimumProcessorUsage)
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
     * @param float $maximumProcessorUsage
     *
     * @return $this
     */
    public function setMaximumProcessorUsage(float $maximumProcessorUsage)
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
     * @param float $minimumMemoryUsage
     *
     * @return $this
     */
    public function setMinimumMemoryUsage(float $minimumMemoryUsage)
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
     * @param float $maximumMemoryUsage
     *
     * @return $this
     */
    public function setMaximumMemoryUsage(float $maximumMemoryUsage)
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
     * @param float $minimumSiblingProcessorUsage
     *
     * @return $this
     */
    public function setMinimumSiblingProcessorUsage(float $minimumSiblingProcessorUsage)
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
     * @param float $maximumSiblingProcessorUsage
     *
     * @return $this
     */
    public function setMaximumSiblingProcessorUsage(float $maximumSiblingProcessorUsage)
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
     * @param float $minimumSiblingMemoryUsage
     *
     * @return $this
     */
    public function setMinimumSiblingMemoryUsage(float $minimumSiblingMemoryUsage)
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
     * @param float $maximumSiblingMemoryUsage
     *
     * @return $this
     */
    public function setMaximumSiblingMemoryUsage(float $maximumSiblingMemoryUsage)
    {
        $this->maximumSiblingMemoryUsage = $maximumSiblingMemoryUsage;

        return $this;
    }
}
