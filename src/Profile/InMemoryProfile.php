<?php

# This file is generated, changes you make will be lost.
# Make your changes in /Users/assertchris/Source/asyncphp/doorman/src/Profile/InMemoryProfile.pre instead.

namespace AsyncPHP\Doorman\Profile;

use AsyncPHP\Doorman\Process;
use AsyncPHP\Doorman\Profile;

final class InMemoryProfile implements Profile
{
    /**
     * @var Process[]
     */
    private $processes;

    public function getProcesses(): array
    {
        return $this->processes ?: [];
    }

    public function setProcesses(array $value)
    {
        $this->processes = $value;
    }

    /**
     * @var float
     */
    private $processorLoad;

    public function getProcessorLoad(): float
    {
        return $this->processorLoad ?: 0.0;
    }

    public function setProcessorLoad(float $value)
    {
        $this->processorLoad = $value;
    }

    /**
     * @var float
     */
    private $memoryLoad;

    public function getMemoryLoad(): float
    {
        return $this->memoryLoad ?: 0.0;
    }

    public function setMemoryLoad(float $value)
    {
        $this->memoryLoad = $value;
    }

    /**
     * @var Process[]
     */
    private $siblingProcesses;

    public function getSiblingProcesses(): array
    {
        return $this->siblingProcesses ?: [];
    }

    public function setSiblingProcesses(array $value)
    {
        $this->siblingProcesses = $value;
    }

    /**
     * @var float
     */
    private $siblingProcessorLoad;

    public function getSiblingProcessorLoad(): float
    {
        return $this->siblingProcessorLoad ?: [];
    }

    public function setSiblingProcessorLoad(float $value)
    {
        $this->siblingProcessorLoad = $value;
    }

    /**
     * @var float
     */
    private $siblingMemoryLoad;

    public function getSiblingMemoryLoad(): float
    {
        return $this->siblingMemoryLoad ?: 0.0;
    }

    public function setSiblingMemoryLoad(float $value)
    {
        $this->siblingMemoryLoad = $value;
    }
}
