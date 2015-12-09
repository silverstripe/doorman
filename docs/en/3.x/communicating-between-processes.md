# Communicating Between Processes

Communicating between different threads, processes or servers can be difficult. Knowing when another processes has stopped is sometimes not enough. Perhaps we need to send status updates. Perhaps we need to communicate a failure or blocking state. Perhaps we need to consult another process for what to do next.

We can overcome this problem by using a communication layer, like [Remit](https://github.com/asyncphp/remit). Remit is a distributed event emitter. You can create an event listener collector (server) in the same place you create a manager:

 ```php
$manager = new ProcessManager();

$server = new ZeroMqServer(
    new InMemoryLocation("127.0.0.1", 5555)
);

$server->addListener("message-from-child-process", function ($message) {
    print "message from child process: {$message}\n";
});

do {
    // check for new remit events
    $server->tick();

    if ($task = $this->getNextTask()) {
        $manager->addTask($task);
    }

    usleep(250);
} while ($manager->tick());
 ```

 You can replace `message-from-child-process` with event names that suit your application. Then you can create emit corresponding events from within your tasks, by creating an event emitter (client):

 ```php
 new ProcessCallbackTask(function () {
     $client = new ZeroMqClient(
         new InMemoryLocation("127.0.0.1", 5555)
     );

     $client->emit(
         "message-from-child-process", ["this is a message from " . getmypid()]
     );
 });
 ```

 You'll need to make sure the `host` and `port` constructor arguments, of the `InMemoryLocation` object match. The Remit server and client need to connect to the same location in order to share messages with each other.

 It's also possible to create a server within a child process task, and have the server emit messages to it. You'll just need a new location for those messages. You shouldn't share locations for server/client pairs that send messages in different directions. That'll cause chaos!

 One of the goals of Doorman is to require as few extra extensions as possible. In fact, Doorman requires no extra extensions. Unfortunately, Remit (at least the `ZeroMqServer` and `ZeroMqClient` classes) require the ZeroMQ extension. As we support more message queue providers, you'll be able to pick the provider that suits your environment.
