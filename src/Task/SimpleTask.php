<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Task;
use Jeremeamia\SuperClosure\SerializableClosure;

class SimpleTask implements Task
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        // TODO handle malformed callback

        $this->callback = $callback;
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
