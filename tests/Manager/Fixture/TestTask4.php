<?php

namespace AsyncPHP\Doorman\Tests\Manager\Fixture;

use AsyncPHP\Doorman\Task\ProcessCallbackTask;

class TestTask4 extends ProcessCallbackTask
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Tests\\Manager\\Fixture\\TestHandler4";
    }

    /**
     * @inheritdoc
     *
     * @return int
     */
    public function getExpiresIn()
    {
        return 2;
    }
}
