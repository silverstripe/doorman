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
     * @var null|float
     */
    protected $cpu;

    /**
     * @var null|float
     */
    protected $memory;

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
     * @return null|float
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * Sets the CPU load for this process.
     *
     * @param float $cpu
     *
     * @return $this
     */
    public function setCpu($cpu)
    {
        $this->cpu = $cpu;

        return $this;
    }

    /**
     * Gets the memory load for this process.
     *
     * @return null|float
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Sets the memory load for this process.
     *
     * @param float $memory
     *
     * @return $this
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }
}
