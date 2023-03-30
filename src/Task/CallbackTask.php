<?php

namespace AsyncPHP\Doorman\Task;

use AsyncPHP\Doorman\Task;
use Closure;
use Opis\Closure\SerializableClosure;

class CallbackTask implements Task
{
    /**
     * @var Closure
     */
    private $closure;

    /**
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @inheritdoc
     *
     * @return string
     * @deprecated 3.13.0 Will be replaced with __serialize() in 4.0.0
     */
    public function serialize()
    {
        $closure = new SerializableClosure($this->closure);

        return serialize($closure);
    }

    /**
     * @inheritdoc
     *
     * @param string $serialized
     * @deprecated 3.13.0 Will be replaced with __unserialize() in 4.0.0
     */
    public function unserialize($serialized)
    {
        /** @var SerializableClosure $closure */
        $closure = unserialize($serialized);

        $this->closure = $closure->getClosure();
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getData()
    {
        return [
            "closure" => $this->closure,
        ];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Handler\\CallbackHandler";
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function ignoresRules()
    {
        return false;
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function stopsSiblings()
    {
        return false;
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function canRunTask()
    {
        return true;
    }
}
