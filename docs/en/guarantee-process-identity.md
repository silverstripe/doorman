# Guarantee Process Identity

One of the subtle problems with the approach we've chosen is that servers creating extremely high amounts of processes could report unexpected process identifiers. When new child processes are created, we immediately query the identifier of the latest process.

If multiple processes are created almost exactly at the same time, there's a small chance that the process identifier returned is not the process identifier of the assigned task.

It's possible to mitigate this by [communicating between processes](communicating-between-processes.md) to establish the PID of a child process. Here's how you can do that. First we should create a slightly altered task class:

```php
class SafeProcessCallbackTask extends ProcessCallbackTask
{
    /**
     * A unique identifier for this task.
     *
     * @var string
     */
    protected $hash;

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @inheritdoc
     *
     * @see CallbackTask::serialize()
     *
     * @return string
     */
    public function serialize()
    {
        $closure = new SerializableClosure($this->closure);

        return serialize(array(
            "hash"    => $this->hash,
            "closure" => $closure,
        ));
    }

    /**
     * @inheritdoc
     *
     * @see CallbackTask::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        /** @var array $data */
        $data = unserialize($serialized);

        $this->hash    = $data["hash"];
        $this->closure = $data["closure"]->getClosure();
    }
}
```

This lets us assign a unique hash to our tasks, so that we can reliably associate data with them later. We need to assign this before they start working:

```php
$manager = new ProcessManager();

// create a Remit server, called $server

$server->addListener("pid", function ($hash, $pid) {
    foreach ($this->running as $task) {
        if ($task->getHash() === $hash) {
            $task->setPid($pid);
        }
    }
});

do {
    $server->tick();

    if ($task = $this->getNextTask()) {
        $task->setHash(spl_object_hash($task));

        $manager->addTask($task);
    }

    usleep(250);
} while ($manager->tick());
```

Then, in the child task we emit the PID of the child process:

```php
protected function getNextTask()
{
    return new SafeProcessCallbackTask(function () {
        // create a Remit client, called $client

        $client->emit(
            "pid", [$this->getHash(), getmypid()]
        );
    });
}
```

Now, even if the task has the wrong PID initially, it will get the correct PID the moment the child process starts. You can even adjust the sleep time between new tasks being added to the process manager, if you want to make sure the correct PID has reached the manager before it is used:

```php
do {
    if ($task = $this->getNextTask()) {
        $task->setHash(spl_object_hash($task));

        $manager->addTask($task);
    }

    // added time to allow for all PID updates
    sleep(1);

    // check for updated PID before using it
    $server->tick();

} while ($manager->tick());
```
