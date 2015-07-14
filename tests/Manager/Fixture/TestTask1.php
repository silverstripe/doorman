<?php

namespace AsyncPHP\Doorman\Tests\Manager\Fixture;

use AsyncPHP\Doorman\Task\ShellTask;

class TestTask1 extends ShellTask
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
