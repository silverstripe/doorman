<?php

$paths = array(
    __DIR__."/../vendor/autoload.php",
    __DIR__."/../../../autoload.php",
);

foreach ($paths as $path) {
    if (file_exists($path)) {
        require $path;
    }
}

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;

if (count($argv) < 2) {
    throw new InvalidArgumentException("Invalid call");
}

$script = array_shift($argv);

$task = array_shift($argv);

/**
 * We must account for the input data being malformed. That's why we use "@".
 */
$task = @unserialize(base64_decode($task));

if ($task instanceof Task) {
    $handler = $task->getHandler();

    $object = new $handler();

    if ($object instanceof Handler) {
        $object->handle($task);
    }
}
