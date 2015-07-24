# Getting Started

The easiest way to install is by using [Composer](https://getcomposer.org):

```sh
$ composer require asyncphp/doorman
```

## Simple Tasks

You can run simple, synchronous tasks:

```php
use AsyncPHP\Doorman\Manager\SynchronousManager;
use AsyncPHP\Doorman\Task\CallbackTask;

$manager = new SynchronousManager();

$task1 = new CallbackTask(function () {
    print "in task 1";
});

$task2 = new CallbackTask(function () {
    print "in task 2";
});

$manager->addTask($task1);
$manager->addTask($task2);

while ($manager->tick()) {
    usleep(250);
}
```

Running `SynchronousManager->tick()` once is like working for a single processor cycle. All waiting tasks are started and completed tasks are removed. When there are no more waiting or running tasks then `tick()` will return false. This makes it ideal as a while loop condition. The loop will run until there manager has handled all tasks.

Running tasks synchronously isn't very useful. What we want is to be able to run tasks in parallel. for this, we need to use a different manager and task:

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$manager = new ProcessManager();

$task1 = new ProcessCallbackTask(function () {
    print "in task 1";
});

$task2 = new ProcessCallbackTask(function () {
    print "in task 2";
});

$manager->addTask($task1);
$manager->addTask($task2);

while ($manager->tick()) {
    usleep(250);
}
```

You may wonder where the task output has disappeared to. `ProcessCallbackTasks` are run in separate processes, so their output is not sent to the same place as the process in which `ProcessManager` is running.

By default, this output is going to `/dev/null` but you can store it in log files:

```php
$manager = new ProcessManager();
$manager->setLogPath("path/to/log");
```

This will store any output in `path/to/log/stdout.log` and `path/to/log/stderr.log`. You may also want to change the path to the PHP binary or the worker script:

```php
$manager = new ProcessManager();
$manager->setBinary("path/to/php");
$manager->setWorker("path/to/worker.php");
```

If you do change the path to the worker script, you'll need to create the required handler for handling tasks. Check `bin/worker.php` to see how.
