<?php

namespace AsyncPHP\Doorman\Tests\Manager\Fixture;

use AsyncPHP\Doorman\Task\ShellTask;

class TestTask2 extends ShellTask
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getHandler()
    {
        return "AsyncPHP\\Doorman\\Tests\\Manager\\Fixture\\TestHandler2";
    }
}
