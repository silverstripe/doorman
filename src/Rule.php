<?php

namespace AsyncPHP\Doorman;

interface Rule
{
    const LIMIT = "limit";
    const HANDLER = "handler";
    const MINIMUM_GLOBAL_PROCESSOR_USAGE = "minimum-global-processor-usage";
    const MAXIMUM_GLOBAL_PROCESSOR_USAGE = "maximum-global-processor-usage";
    const MINIMUM_GLOBAL_MEMORY_USAGE = "minimum-global-memory-usage";
    const MAXIMUM_GLOBAL_MEMORY_USAGE = "maximum-global-memory-usage";
    const MINIMUM_SIBLING_PROCESSOR_USAGE = "minimum-sibling-processor-usage";
    const MAXIMUM_SIBLING_PROCESSOR_USAGE = "maximum-sibling-processor-usage";
    const MINIMUM_SIBLING_MEMORY_USAGE = "minimum-sibling-memory-usage";
    const MAXIMUM_SIBLING_MEMORY_USAGE = "maximum-sibling-memory-usage";

    /**
     * Gets the number of processes that are allowed at once.
     *
     * @return int
     */
    public function getLimit();

    /**
     * Gets the handler to restrict this rule to.
     *
     * @return null|string
     */
    public function getHandler();

    /**
     * @return null|int
     */
    public function getMinimumGlobalProcessorUsage();

    /**
     * @return null|int
     */
    public function getMaximumGlobalProcessorUsage();

    /**
     * @return null|int
     */
    public function getMinimumGlobalMemoryUsage();

    /**
     * @return null|int
     */
    public function getMaximumGlobalMemoryUsage();

    /**
     * @return null|int
     */
    public function getMinimumSiblingProcessorUsage();

    /**
     * @return null|int
     */
    public function getMaximumSiblingProcessorUsage();

    /**
     * @return null|int
     */
    public function getMinimumSiblingMemoryUsage();

    /**
     * @return null|int
     */
    public function getMaximumSiblingMemoryUsage();
}
