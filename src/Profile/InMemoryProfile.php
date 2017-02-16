<?php

namespace AsyncPHP\Doorman\Profile;

use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile;

final class InMemoryProfile implements Profile
{
    /**
     * @var Process[]
     */
    private $processes = [];

    /**
     * @var float
     */
    private $processorLoad;

    /**
     * @var float
     */
    private $memoryLoad;

    /**
     * @var Process[]
     */
    private $siblingProcesses = [];

    /**
     * @var float
     */
    private $siblingProcessorLoad;

    /**
     * @var float
     */
    private $siblingMemoryLoad;

    /**
     * @inheritdoc
     *
     * @return Process[]
     */
    public function getProcesses(): array
    {
        return $this->processes;
    }

    /**
     * @inheritdoc
     *
     * @param Process[] $processes
     *
     * @return $this
     */
    public function setProcesses(array $processes)
    {
        $this->processes = $processes;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getProcessorLoad()
    {
        return $this->processorLoad;
    }

    /**
     * @inheritdoc
     *
     * @param float $processorLoad
     *
     * @return $this
     */
    public function setProcessorLoad(float $processorLoad)
    {
        $this->processorLoad = $processorLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMemoryLoad()
    {
        return $this->memoryLoad;
    }

    /**
     * @inheritdoc
     *
     * @param float $memoryLoad
     *
     * @return $this
     */
    public function setMemoryLoad(float $memoryLoad)
    {
        $this->memoryLoad = $memoryLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return Process[]
     */
    public function getSiblingProcesses(): array
    {
        return $this->siblingProcesses;
    }

    /**
     * @inheritdoc
     *
     * @param Process[] $siblingProcesses
     *
     * @return $this
     */
    public function setSiblingProcesses(array $siblingProcesses)
    {
        $this->siblingProcesses = $siblingProcesses;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getSiblingProcessorLoad()
    {
        return $this->siblingProcessorLoad;
    }

    /**
     * @inheritdoc
     *
     * @param float $siblingProcessorLoad
     *
     * @return $this
     */
    public function setSiblingProcessorLoad(float $siblingProcessorLoad)
    {
        $this->siblingProcessorLoad = $siblingProcessorLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getSiblingMemoryLoad()
    {
        return $this->siblingMemoryLoad;
    }

    /**
     * @inheritdoc
     *
     * @param float $siblingMemoryLoad
     *
     * @return $this
     */
    public function setSiblingMemoryLoad(float $siblingMemoryLoad)
    {
        $this->siblingMemoryLoad = $siblingMemoryLoad;

        return $this;
    }
}
