<?php

namespace AsyncPHP\Doorman\Tests\Manager\Fixture;

use AsyncPHP\Doorman\Task\ProcessCallbackTask;

class TestTask1 extends ProcessCallbackTask
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Tests\\Manager\\Fixture\\TestHandler1";
    }
}
