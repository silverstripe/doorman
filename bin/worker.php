<?php

require __DIR__ . "/../vendor/autoload.php";

use AsyncPHP\Doorman\Handler;
use AsyncPHP\Doorman\Task;

if (count($argv) < 2) {
    throw new InvalidArgumentException("Invalid call");
}

$script = array_shift($argv);

$task = array_shift($argv);
$task = @unserialize(base64_decode($task));

if ($task instanceof Task) {
    $handler = $task->getHandler();

    $object = new $handler();

    if ($object instanceof Handler) {
        $object->handle($task);
    }
}
