<?php

namespace AsyncPHP\Doorman;

interface Profile
{
    /**
     * @return Process[]
     */
    public function getProcesses();

    /**
     * @return float
     */
    public function getProcessorLoad();

    /**
     * @return float
     */
    public function getMemoryLoad();

    /**
     * @return Process[]
     */
    public function getSiblingProcesses();

    /**
     * @return float
     */
    public function getSiblingProcessorLoad();

    /**
     * @return float
     */
    public function getSiblingMemoryLoad();
}
