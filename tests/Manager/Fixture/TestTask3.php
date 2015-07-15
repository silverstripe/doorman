<?php

namespace AsyncPHP\Doorman\Tests\Manager\Fixture;

use AsyncPHP\Doorman\Task\ProcessCallbackTask;

class TestTask3 extends ProcessCallbackTask
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Tests\\Manager\\Fixture\\TestHandler3";
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function stopsSiblings()
    {
        return true;
    }
}
