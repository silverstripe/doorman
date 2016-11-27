# Rules

You can restrict the number of processes that are able to run at a time. You can even control the processor and memory load points at which different restrictions can be applied:

```php
use AsyncPHP\Doorman\Manager\ProcessManager;
use AsyncPHP\Doorman\Rule\InMemoryRule;
use AsyncPHP\Doorman\Task\ProcessCallbackTask;

$rule1 = new InMemoryRule();
$rule1->setProcesses(2);
$rule1->setMinimumProcessorLoad(0);
$rule1->setMaximumProcessorLoad(50);

$rule2 = new InMemoryRule();
$rule2->setProcesses(1);
$rule2->setMinimumProcessorLoad(51);
$rule2->setMaximumProcessorLoad(75);

$rule3 = new InMemoryRule();
$rule3->setProcesses(0);
$rule3->setMinimumProcessorLoad(76);
$rule3->setMaximumProcessorLoad(100);

$manager = new ProcessManager();

$task1 = new ProcessCallbackTask(function () {
    // ...this will always be run
});

$task2 = new ProcessCallbackTask(function () {
    // ...this will only be run if task 1 is using less than 51% processor load
});

$task3 = new ProcessCallbackTask(function () {
    // ...this will not be run until the previous tasks are complete
});

$manager->addTask($task1);
$manager->addTask($task2);
$manager->addTask($task3);

$manager->addRule($rule1);
$manager->addRule($rule2);
$manager->addRule($rule3);

while ($manager->tick()) {
    usleep(250);
}
```

You can also control when the rules apply to different memory loads and whether they only apply to loads of sibling processes or not:

- `InMemoryRule->setMinimumMemoryLoad()`
- `InMemoryRule->setMaximumMemoryLoad()`
- `InMemoryRule->setMinimumSiblingProcessorLoad()`
- `InMemoryRule->setMaximumSiblingProcessorLoad()`
- `InMemoryRule->setMinimumSiblingMemoryLoad()`
- `InMemoryRule->setMaximumSiblingMemoryLoad()`
