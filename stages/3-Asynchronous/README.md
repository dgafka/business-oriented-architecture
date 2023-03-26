# 2. Asynchronous Message Handling

Introduce Notification Service as asynchronous Event Handler.  
Running `run_example.php` will automatically create Queue in `RabbitMQ` and poll from it.

## Solved Common Problems

### 1. Serializing and Deserializing of Event and Commands  

Messages when handled asynchronously require serialization and deserialization as they go over wire.
Serializing and deserializing are not business related problems and takes our focus out of domain logic.   

Ecotone provides automatic conversion for our `Message classes`, so we don't need to deal with it manually.  
This speeds up the development process and let us work on business problems. 
In case we need to serialize/deserialize given class differently we can always take over, or provide our own converters if needed. 

### 2. Queues and Message binding

When we want to handle messages asynchronously we need to create queues and topics.
This leads to a need for customized extra infrastructure configuration in order to deploy our application.

Ecotone provides automatic queue creation. When Message Consumer is started is creates the queue and starts consuming.   
This is opt-in feature, so if you want to create queues manually, or you already have them and want to speed up bootstrap process, then you can disable this.

---

When messages are sent they need to be delivered to the right queue. This is done by binding routing keys to queues, from there messages can be consumed to execute Event/Command Handlers.  
Manual wiring messages often leads to errors and require customized tests to be verified before going live.  
In some cases you may deal with frameworks that will do the binding for you and will deliver the Command or Event to the right handler based on `Message Class`.  
This is not ideal solution as it block us from `refactoring class names or namespaces`, as if the Message is in the Queue it will keep the old name, and it will fail when consumed.

Ecotone provides automatic routing binding, so we don't need to deal with it manually.  
And delivers message to the right handler based on `Endpoint Id`. This is unique identifier hold by `Message Handler`.  

```php
    #[Asynchronous("notificationChannel")]
    #[EventHandler(endpointId: "notificationService")]
    public function sendNotification(UserWasRegistered $event, Notifier $notifier): void
```

This as a result allow us to fully refactor `classes` and `namespaces` without breaking the system.

### 3. Testing asynchronous flows

Testing flows that are asynchronous can be really cumbersome without proper tooling.  
It involves running asynchronous `Message Consumer` which is a separate process.    
We need to control this process so it can be stopped as fast as it's possible without creating zombie processes.  
It works in second process, which means we can't use in memory implementation and often need to build special solution to make correct assertions.

Ecotone provides easy way to run `Message Consumers` within same process with possibility to stop them quickly, check `NotificationServiceTest`.
Due to fact that they are run within same process, we can use In Memory implementation, which simplify assertions.  
Besides that we can run `In Memory` Message Channels, instead of real like implementation like `RabbitMQ`, when full integration test is not needed.  
This makes the test really quick and easy to write. 