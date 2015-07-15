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
    public function getProcesses();

    /**
     * @param float $processorLoad
     */
    public function setProcessorLoad($processorLoad);

    /**
     * @return float
     */
    public function getProcessorLoad();

    /**
     * @param float $memoryLoad
     */
    public function setMemoryLoad($memoryLoad);

    /**
     * @return float
     */
    public function getMemoryLoad();

    /**
     * @param Process[] $siblingProcesses
     */
    public function setSiblingProcesses(array $siblingProcesses);

    /**
     * @return Process[]
     */
    public function getSiblingProcesses();

    /**
     * @param float $siblingProcessorLoad
     */
    public function setSiblingProcessorLoad($siblingProcessorLoad);

    /**
     * @return float
     */
    public function getSiblingProcessorLoad();

    /**
     * @param float $siblingMemoryLoad
     */
    public function setSiblingMemoryLoad($siblingMemoryLoad);

    /**
     * @return float
     */
    public function getSiblingMemoryLoad();
}
