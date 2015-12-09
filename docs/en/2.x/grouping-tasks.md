# Grouping Tasks

Sometimes you may need to group multiple tasks together:

```php
$manager = new ProcessManager();

$manager->addTask(...);
$manager->addTask(...);
$manager->addTask(...);

while ($manager->tick()) {
    usleep(25000);
}

$manager->addTask(...);
$manager->addTask(...);
$manager->addTask(...);

while ($manager->tick()) {
    usleep(25000);
}
```

Perhaps you have a number of pre-condition tasks, which can be executed in any order but need to happen before your main tasks can execute. You could keep emptying the manager task queue, or you could use the `GroupProcessManager` decorator:

```php
$manager = new GroupProcessManager(new ProcessManager());

// add an array of pre-condition tasks
$manager->addTaskGroup(array(...));

$manager->addTaskGroup(array(...));

// add a single post-condition task
$manager->addTask(...);

while ($manager->tick()) {
    usleep(25000);
}
```

You can either add an array of tasks or you can add a single task. Remember that single tasks are not run in parallel, but are wrapped in an array and sent to the same `addTaskGroup()` method. Tasks within a group are run in parallel, assuming they are allowed by the rules applied to the decorated `Manager`.

Groups will be handled in a predictable order, and subsequent groups will wait until precedent groups are completed.

You can also call any method on the decorated `Manager`, as missing method calls are delegated to it.
