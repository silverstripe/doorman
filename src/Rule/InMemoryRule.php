<?php

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;
use Concat\Config\Container\AbstractContainer;
use Concat\Config\Container\Value;

final class InMemoryRule extends AbstractContainer implements Rule
{
    /**
     * @inheritdoc
     *
     * @return int|null
     */
    public function getProcesses()
    {
        return $this->get("processes");
    }

    /**
     * @inheritdoc
     *
     * @return null|string
     */
    public function getHandler()
    {
        return $this->get("handler");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumProcessorUsage()
    {
        return $this->get("handlers.processor.minimum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumProcessorUsage()
    {
        return $this->get("handlers.processor.maximum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumMemoryUsage()
    {
        return $this->get("handlers.memory.minimum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumMemoryUsage()
    {
        return $this->get("handlers.memory.maximum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumSiblingProcessorUsage()
    {
        return $this->get("siblings.processor.minimum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumSiblingProcessorUsage()
    {
        return $this->get("siblings.processor.maximum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMinimumSiblingMemoryUsage()
    {
        return $this->get("siblings.memory.minimum");
    }

    /**
     * @inheritdoc
     *
     * @return null|float
     */
    public function getMaximumSiblingMemoryUsage()
    {
        return $this->get("siblings.memory.maximum");
    }

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    protected function getExpectedTypes()
    {
        return [
            "handler" => [null, Value::TYPE_STRING],
            "processes" => Value::TYPE_INTEGER,
            "handlers" => [
                "processor" => [
                    "minimum" => Value::TYPE_FLOAT,
                    "maximum" => Value::TYPE_FLOAT,
                ],
                "memory" => [
                    "minimum" => Value::TYPE_FLOAT,
                    "maximum" => Value::TYPE_FLOAT,
                ],
            ],
            "siblings" => [
                "processor" => [
                    "minimum" => Value::TYPE_FLOAT,
                    "maximum" => Value::TYPE_FLOAT,
                ],
                "memory" => [
                    "minimum" => Value::TYPE_FLOAT,
                    "maximum" => Value::TYPE_FLOAT,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    protected function getDefaultValues()
    {
        return [
            "handler" => null,
            "processes" => -1,
            "handlers" => [
                "processor" => [
                    "minimum" => 0,
                    "maximum" => 100,
                ],
                "memory" => [
                    "minimum" => 0,
                    "maximum" => 100,
                ],
            ],
            "siblings" => [
                "processor" => [
                    "minimum" => 0,
                    "maximum" => 100,
                ],
                "memory" => [
                    "minimum" => 0,
                    "maximum" => 100,
                ],
            ],
        ];
    }
}
