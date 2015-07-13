<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Process;

class ShellTask extends SimpleTask implements Process
{
    /**
     * @var null|int
     */
    protected $id;

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the CPU load for this process.
     *
     * @return float
     */
    public function getCpu()
    {
        // TODO: Implement getCpu() method.
    }

    /**
     * Sets the CPU load for this process.
     *
     * @param float $cpu
     */
    public function setCpu($cpu)
    {
        // TODO: Implement setCpu() method.
    }

    /**
     * Gets the memory load for this process.
     *
     * @return float
     */
    public function getMemory()
    {
        // TODO: Implement getMemory() method.
    }

    /**
     * Sets the memory load for this process.
     *
     * @param float $memory
     */
    public function setMemory($memory)
    {
        // TODO: Implement setMemory() method.
    }
}
