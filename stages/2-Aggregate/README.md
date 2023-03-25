# 1. CQRS

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
