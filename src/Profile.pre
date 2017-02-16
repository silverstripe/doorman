<?php

namespace AsyncPHP\Doorman;

interface Profile
{
    /**
     * @param Process[] $processes
     */
    public function setProcesses(array $processes);

    /**
     * @return Process[]
     */
    public function getProcesses(): array;

    /**
     * @param float $processorLoad
     */
    public function setProcessorLoad(float $processorLoad);

    /**
     * @return null|float
     */
    public function getProcessorLoad();

    /**
     * @param float $memoryLoad
     */
    public function setMemoryLoad(float $memoryLoad);

    /**
     * @return null|float
     */
    public function getMemoryLoad();

    /**
     * @param Process[] $siblingProcesses
     */
    public function setSiblingProcesses(array $siblingProcesses);

    /**
     * @return Process[]
     */
    public function getSiblingProcesses(): array;

    /**
     * @param float $siblingProcessorLoad
     */
    public function setSiblingProcessorLoad(float $siblingProcessorLoad);

    /**
     * @return null|float
     */
    public function getSiblingProcessorLoad();

    /**
     * @param float $siblingMemoryLoad
     */
    public function setSiblingMemoryLoad(float $siblingMemoryLoad);

    /**
     * @return null|float
     */
    public function getSiblingMemoryLoad();
}
