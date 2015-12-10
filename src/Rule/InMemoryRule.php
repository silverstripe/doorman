<?php

namespace AsyncPHP\Doorman\Rule;

use AsyncPHP\Doorman\Rule;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class InMemoryRule implements Rule
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $defaults = [
        Rule::LIMIT => 0,
        Rule::HANDLER => null,
        Rule::MINIMUM_GLOBAL_PROCESSOR_USAGE => null,
        Rule::MAXIMUM_GLOBAL_PROCESSOR_USAGE => null,
        Rule::MINIMUM_GLOBAL_MEMORY_USAGE => null,
        Rule::MAXIMUM_GLOBAL_MEMORY_USAGE => null,
        Rule::MINIMUM_SIBLING_PROCESSOR_USAGE => null,
        Rule::MAXIMUM_SIBLING_PROCESSOR_USAGE => null,
        Rule::MINIMUM_SIBLING_MEMORY_USAGE => null,
        Rule::MAXIMUM_SIBLING_MEMORY_USAGE => null,
    ];

    /**
     * @var array
     */
    private $types = [
        Rule::LIMIT => ["int"],
        Rule::HANDLER => ["null", "string"],
        Rule::MINIMUM_GLOBAL_PROCESSOR_USAGE => ["null", "int"],
        Rule::MAXIMUM_GLOBAL_PROCESSOR_USAGE => ["null", "int"],
        Rule::MINIMUM_GLOBAL_MEMORY_USAGE => ["null", "int"],
        Rule::MAXIMUM_GLOBAL_MEMORY_USAGE => ["null", "int"],
        Rule::MINIMUM_SIBLING_PROCESSOR_USAGE => ["null", "int"],
        Rule::MAXIMUM_SIBLING_PROCESSOR_USAGE => ["null", "int"],
        Rule::MINIMUM_SIBLING_MEMORY_USAGE => ["null", "int"],
        Rule::MAXIMUM_SIBLING_MEMORY_USAGE => ["null", "int"],
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();

        foreach ($this->defaults as $option => $default) {
            $resolver->setDefault($option, $default);
        }

        foreach ($this->types as $option => $types) {
            $resolver->setAllowedTypes($option, $types);
        }

        $this->options = $resolver->resolve($options);
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->options[Rule::LIMIT];
    }

    /**
     * @inheritdoc
     *
     * @return null|string
     */
    public function getHandler()
    {
        return $this->options[Rule::HANDLER];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMinimumGlobalProcessorUsage()
    {
        return $this->options[Rule::MINIMUM_GLOBAL_PROCESSOR_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMaximumGlobalProcessorUsage()
    {
        return $this->options[Rule::MAXIMUM_GLOBAL_PROCESSOR_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMinimumGlobalMemoryUsage()
    {
        return $this->options[Rule::MINIMUM_GLOBAL_MEMORY_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMaximumGlobalMemoryUsage()
    {
        return $this->options[Rule::MAXIMUM_GLOBAL_MEMORY_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMinimumSiblingProcessorUsage()
    {
        return $this->options[Rule::MINIMUM_SIBLING_PROCESSOR_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMaximumSiblingProcessorUsage()
    {
        return $this->options[Rule::MAXIMUM_SIBLING_PROCESSOR_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMinimumSiblingMemoryUsage()
    {
        return $this->options[Rule::MINIMUM_SIBLING_MEMORY_USAGE];
    }

    /**
     * @inheritdoc
     *
     * @return null|int
     */
    public function getMaximumSiblingMemoryUsage()
    {
        return $this->options[Rule::MAXIMUM_SIBLING_MEMORY_USAGE];
    }
}
