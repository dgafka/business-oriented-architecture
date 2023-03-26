# 2. Aggregates

Introduce `User` as `Aggregate`, which publish `UserWasRegistered` event.

## Solved Common Problems

### 1. A lot of boilerplate for calling aggregates  

When working with Aggregates/Entities we often need to create a lot of boilerplate code to call them.  
This boilerplate lands in `application layer` and looks like:  

```php
class SomeService
{
    #[CommandHandler]
    public function doSomething(DoSomething $command) : void
    {
        $aggregate = $this->aggregateRepository->get($command->getId()
        $aggregate->doSomething($command->getSomeParameter());
        $this->aggregateRepository->save($aggregate);
    }
}
```

This code is not related to business logic and is only needed to fulfil technical requirements of `fetching` and `storing` our aggregate.
Ecotone solves this giving us a possibility to call the aggregate directly. 
This allows us to remove the boilerplate and focus on business logic only.  

```php
#[Aggregate]
class SomeAggregate
{
    #[CommandHandler]
    public function doSomething(DoSomething $command) : void
    {
        // doing something
    }
}
```

### 2. Boilerplate Command and Query classes

It often happens that we are required to create a `Command Class` whenever we need expose new `Command Handler`.  
This even happens for situations, when aggregate itself does not require any parameters. 

```php
    #[CommandHandler]
    public function block(BlockUser $command) : void
    {
        $this->isBlocked = true;
    }
```
The Command Class is not necessary boilerplate which only blurs the business logic.  
Ecotone solves this by allowing us to call the aggregate directly using `command routing`.    

```php
#[Aggregate]
class User
{
    #[CommandHandler("user.block")]
    public function block(): void
    {
        $this->isBlocked = true;
    }
}
```

Then using Command Bus we are sending as `metadata` aggregate id, so Ecotone can know which instance to fetch and execute.

```php
$commandBus->sendWithRouting("user.block", metadata: ["aggregate.id" => 1]);
```

### 3. Lack of isolation for testing flows

When our application starts publishing events it becomes cumbersome to test the flows.  
This is due to events triggers side effects, which in result triggers part of the system that were not supposed to be tested in given scenario.  
Due to difficulties in writing such tests we often write few of them, or spend enormous amount of time on making them work correctly.
 
Ecotone provides a way to set up a test for given set of classes, therefore we only test what we want to test, and we do it in isolation.
If you take a look on `UserTest` you will see that even so that even that `UserWasRegistered` event is published it does not trigger `NotificationService` 
as we have not included it into `test scenario`.

```php
EcotoneLite::bootstrapFlowTesting(
    [User::class, InMemoryUserRepository::class], // Classes containing Ecotone's attributes that we want to test
    [new InMemoryUserRepository()] // Services available in test scenario, we may also pass Dependency Container
);
```

Flow tests in Ecotone are fast and easy to write, actually you may treat it as a part of your unit test suite, which verify behaviour of your aggregates.


