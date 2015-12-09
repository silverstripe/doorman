# Using Event Loops

Doorman works well alongside event loop implementations like [Icicle](https://github.com/icicleio) and [ReactPHP](https://github.com/reactphp).

## Icicle

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$manager = new ProcessManager();

$task = new ProcessCallbackTask(function () {
    for ($i = 0; $i < 5; $i++) {
        print "child tick {$i}";
        sleep(1);
    }
});

$manager->addTask($task);

Icicle\Loop\periodic(0.1, function () use ($manager) {
    if (!$manager->tick()) {
        Icicle\Loop\stop();
    }
});

Icicle\Loop\run();
```

## ReactPHP

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$manager = new ProcessManager();

$task = new ProcessCallbackTask(function () {
    for ($i = 0; $i < 5; $i++) {
        print "child tick {$i}";
        sleep(1);
    }
});

$manager->addTask($task);

$loop = React\EventLoop\Factory::create();

$loop->addPeriodicTimer(0.1, function () use ($manager, $loop) {
    if (!$manager->tick()) {
        $loop->stop();
    }
});

$loop->run();
```

Using Doorman with an infinite loop makes the whole thing a blocking process. If you use Doorman with one of these event loop implementations, you can perform other non-blocking operations while monitoring child processes. It's great!
 