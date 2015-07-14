<?php

namespace AsyncPHP\Doorman\Profile;

use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile;

class InMemoryProfile implements Profile
{
    /**
     * @var Process[]
     */
    protected $processes = array();

    /**
     * @var float
     */
    protected $processorLoad = 0.0;

    /**
     * @var float
     */
    protected $memoryLoad = 0.0;

    /**
     * @var Process[]
     */
    protected $siblingProcesses = array();

    /**
     * @var float
     */
    protected $siblingProcessorLoad = 0.0;

    /**
     * @var float
     */
    protected $siblingMemoryLoad = 0.0;

    /**
     * @inheritdoc
     *
     * @return Process[]
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
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
     * @return float
     */
    public function getProcessorLoad()
    {
        return $this->processorLoad;
    }

    /**
     * @param float $processorLoad
     *
     * @return $this
     */
    public function setProcessorLoad($processorLoad)
    {
        $this->processorLoad = $processorLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return float
     */
    public function getMemoryLoad()
    {
        return $this->memoryLoad;
    }

    /**
     * @param float $memoryLoad
     *
     * @return $this
     */
    public function setMemoryLoad($memoryLoad)
    {
        $this->memoryLoad = $memoryLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return Process[]
     */
    public function getSiblingProcesses()
    {
        return $this->siblingProcesses;
    }

    /**
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
     * @return float
     */
    public function getSiblingProcessorLoad()
    {
        return $this->siblingProcessorLoad;
    }

    /**
     * @param float $siblingProcessorLoad
     *
     * @return $this
     */
    public function setSiblingProcessorLoad($siblingProcessorLoad)
    {
        $this->siblingProcessorLoad = $siblingProcessorLoad;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return float
     */
    public function getSiblingMemoryLoad()
    {
        return $this->siblingMemoryLoad;
    }

    /**
     * @param float $siblingMemoryLoad
     *
     * @return $this
     */
    public function setSiblingMemoryLoad($siblingMemoryLoad)
    {
        $this->siblingMemoryLoad = $siblingMemoryLoad;

        return $this;
    }
}
