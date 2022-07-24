<?php

namespace AsyncPHP\Doorman\Tests;

use PHPUnit\Framework\TestCase;

abstract class Test extends TestCase
{
    /**
     * Safely deletes a file.
     *
     * @param string $file
     */
    protected function unlink($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
