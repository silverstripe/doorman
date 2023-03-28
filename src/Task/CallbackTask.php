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

    public function __serialize(): array
    {
        $closure = new SerializableClosure($this->closure);
        return [
            'closure' => serialize($closure)
        ];
    }

    public function __unserialize(array $data): void
    {
        /** @var SerializableClosure $closure */
        $closure = unserialize($data['closure']);
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
