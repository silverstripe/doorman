# Extending Tasks

The way to define tasks is to implement `Task`, `Process` and `Expires` interfaces. The `SynchronousManager` only requires the first one, but you'll want to use the second and third to take advantage of all the features of `ProcessManager`.

For example, If you create a `Task` which doesn't implement `Process` then `ProcessManager` will not know when it has completed, or be able to stop it prematurely. If you create a `Task` which doesn't implement `Expires` then `ProcessManager` will not know when tasks have been running longer than expected, and be able to stop them.

## Ignoring Rules

Sometimes you'll want tasks to execute, regardless of the rules and concurrency limits in play:

```php
use AsyncPHP\Doorman\Task;

class ImportantTask implements Task
{
    public function ignoresRules()
    {
        return true;
    }

    // ...remaining methods
}
```

Other times you'll want tasks to stop all other tasks of the same type. This is useful for things like search engine re-indexing tasks or sending batches of notifications:

```php
use AsyncPHP\Doorman\Task;

class SelfishTask implements Task
{
    public function stopsSiblings()
    {
        return true;
    }

    // ...remaining methods
}
```
