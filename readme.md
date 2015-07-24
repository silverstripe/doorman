# Doorman

[![Build Status](http://img.shields.io/travis/asyncphp/doorman.svg?style=flat-square)](https://travis-ci.org/asyncphp/doorman)
[![Code Quality](http://img.shields.io/scrutinizer/g/asyncphp/doorman.svg?style=flat-square)](https://scrutinizer-ci.com/g/asyncphp/doorman)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/asyncphp/doorman.svg?style=flat-square)](https://scrutinizer-ci.com/g/asyncphp/doorman)
[![Version](http://img.shields.io/packagist/v/asyncphp/doorman.svg?style=flat-square)](https://packagist.org/packages/asyncphp/doorman)
[![License](http://img.shields.io/packagist/l/asyncphp/doorman.svg?style=flat-square)](license.md)

Child process management.

## Examples

You can use the simple task and handler. These tasks will be executed in a blocking sequence.

```php
use AsyncPHP\Doorman\Task\CallbackTask;
use AsyncPHP\Doorman\Manager\SynchronousManager;

$task1 = new CallbackTask(function () {
    print "task 1 complete";
});

$task2 = new CallbackTask(function () {
    print "task 2 complete";
});

$manager = new SynchronousManager();

$manager->addTask($task1);
$manager->addTask($task2);

while ($this->manager->tick()) {
    usleep(250);
}
```

You can also implement your own tasks and handlers. Using the simple manager will still make these tasks execute in a blocking sequence.

```php
use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;
use AsyncPHP\Doorman\Manager\SynchronousManager;

class MyHandler implements Handler
{
    public function handle(Task $task)
    {
        $data = $task->getData();

        // process task
    }
}

class MyTask implements Task
{
    public function getHandler()
    {
        return "MyHandler";
    }

    public function getData()
    {
        return array(
            "foo" => "bar",
        );
    }

    // implement serialization methods
}

$manager = new SynchronousManager();

$manager->addTask(new MyTask());

while ($this->manager->tick()) {
    usleep(250);
}
```

The `Task` interface extends the `Serializable` interface. You'll need to make sure your tasks can be serialized.

If you want to start running tasks in parallel, then try something like:

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$task1 = new ProcessCallbackTask(function () {
    touch(__DIR__ . "/task1.tmp");
});

$task2 = new ProcessCallbackTask(function () {
    touch(__DIR__ . "/task2.tmp");
});

$manager = new ProcessManager();

$manager->addTask($task1);
$manager->addTask($task2);

while ($manager->tick()) {
    usleep(250);
}
```
