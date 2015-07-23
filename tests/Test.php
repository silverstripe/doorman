<?php

namespace AsyncPHP\Doorman\Tests;

use PHPUnit_Framework_TestCase;

abstract class Test extends PHPUnit_Framework_TestCase
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
