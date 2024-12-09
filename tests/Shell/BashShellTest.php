<?php

namespace AsyncPHP\Doorman\Tests\Shell;

use AsyncPHP\Doorman\Shell\BashShell;
use PHPUnit\Framework\TestCase;

class BashShellTest extends TestCase
{
    /**
     * @var BashShell
     */
    protected $shell;

    public function setUp(): void
    {
        parent::setUp();

        $this->shell = new BashShell();
    }

    public function testExecutesShellCommands()
    {
        $this->assertEquals(array("hello world"), $this->shell->exec("echo %s", array("hello world")));
    }
}
