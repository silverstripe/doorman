<?php

namespace AsyncPHP\Doorman\Tests\Shell;

use AsyncPHP\Doorman\Shell\BashShell;
use AsyncPHP\Doorman\Tests\Test;

/**
 * @covers AsyncPHP\Doorman\Shell\BashShell
 */
class BashShellTest extends Test
{
    /**
     * @var BashShell
     */
    protected $shell;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->shell = new BashShell();
    }

    /**
     * @test
     */
    public function executesShellCommands()
    {
        $this->assertEquals("hello world", $this->shell->exec("echo %s", array("hello world")));
    }
}
