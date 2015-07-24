# Doorman

[![Build Status](http://img.shields.io/travis/asyncphp/doorman.svg?style=flat-square)](https://travis-ci.org/asyncphp/doorman)
[![Code Quality](http://img.shields.io/scrutinizer/g/asyncphp/doorman.svg?style=flat-square)](https://scrutinizer-ci.com/g/asyncphp/doorman)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/asyncphp/doorman.svg?style=flat-square)](https://scrutinizer-ci.com/g/asyncphp/doorman)
[![Version](http://img.shields.io/packagist/v/asyncphp/doorman.svg?style=flat-square)](https://packagist.org/packages/asyncphp/doorman)
[![License](http://img.shields.io/packagist/l/asyncphp/doorman.svg?style=flat-square)](license.md)

Child process management.

## Usage

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$task1 = new ProcessCallbackTask(function () {
    print "in task 1";
});

$task2 = new ProcessCallbackTask(function () {
    print "in task 2";
});

$manager = new ProcessManager();

$manager->addTask($task1);
$manager->addTask($task2);

while ($manager->tick()) {
    usleep(250);
}
```
