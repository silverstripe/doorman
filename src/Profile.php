<?php

namespace AsyncPHP\Doorman;

interface Profile
{
    /**
     * @todo description
     *
     * @param Process[] $processes
     */
    public function setProcesses(array $processes);

    /**
     * @todo description
     *
     * @return Process[]
     */
    public function getProcesses();

    /**
     * @todo description
     *
     * @param float $processorLoad
     */
    public function setProcessorLoad($processorLoad);

    /**
     * @todo description
     *
     * @return float
     */
    public function getProcessorLoad();

    /**
     * @todo description
     *
     * @param float $memoryLoad
     */
    public function setMemoryLoad($memoryLoad);

    /**
     * @todo description
     *
     * @return float
     */
    public function getMemoryLoad();

    /**
     * @todo description
     *
     * @param Process[] $siblingProcesses
     */
    public function setSiblingProcesses(array $siblingProcesses);

    /**
     * @todo description
     *
     * @return Process[]
     */
    public function getSiblingProcesses();

    /**
     * @todo description
     *
     * @param float $siblingProcessorLoad
     */
    public function setSiblingProcessorLoad($siblingProcessorLoad);

    /**
     * @todo description
     *
     * @return float
     */
    public function getSiblingProcessorLoad();

    /**
     * @todo description
     *
     * @param float $siblingMemoryLoad
     */
    public function setSiblingMemoryLoad($siblingMemoryLoad);

    /**
     * @todo description
     *
     * @return float
     */
    public function getSiblingMemoryLoad();
}
