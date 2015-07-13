<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Task;
use InvalidArgumentException;
use Jeremeamia\SuperClosure\SerializableClosure;

class SimpleTask implements Task
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var null|int
     */
    protected $pid;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException("Invalid callable");
        }

        $this->callback = $callback;
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @inheritdoc
     *
     * @param int $pid
     *
     * @return $this
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            "callback" => $this->callback,
        );
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function serialize()
    {
        $closure = new SerializableClosure($this->callback);

        return serialize($closure);
    }

    /**
     * @inheritdoc
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        /** @var SerializableClosure $closure */
        $closure = unserialize($serialized);

        $this->callback = $closure->getClosure();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Handler\\SimpleHandler";
    }
}
