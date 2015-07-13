# Doorman

Child process management.

## Examples

You can use the simple task and handler. These tasks will be executed in a blocking sequence.

```php
use AsyncPHP\Doorman\Task\SimpleTask;
use AsyncPHP\Doorman\Manager\SimpleManager;

$task1 = new SimpleTask(function () {
    print "task 1 complete";
});

$task2 = new SimpleTask(function () {
    print "task 2 complete";
});

$manager = new SimpleManager();

$manager->addTask($task1);
$manager->addTask($task2);

while($manager->tick()) {
    usleep(500);
}
```

You can also implement your own tasks and handlers. Using the simple manager will still make these tasks execute in a blocking sequence.

```php
use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;
use AsyncPHP\Doorman\Manager\SimpleManager;

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

$manager = new SimpleManager();

$manager->addTask(new MyTask());

while($manager->tick()) {
    usleep(500);
}
```

The `Task` interface extends the `Serializable` interface. You'll need to make sure your tasks can be serialized.
