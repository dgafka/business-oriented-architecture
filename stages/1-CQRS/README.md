# 1. CQRS

Introduce `UserService` as `Command` and `Query` Handler.

## Solved Common Problems

### 1. Need for manual wiring of services to connect them to messaging infrastructure  

Often there is tendency for the need of manual wiring your services to messaging configuration, using YAMLs or some external PHP configuration.  
This shifts the focus out of the business related code to the technical parts of the system.  
And need to be maintained in the long term.

In case of Ecotone this is solved using attributes and using declarative way of configuring things.  
Ecotone `resolves your attributes and connect your classes accordingly` to messaging infrastructure.

### 2. Using multiple classes for invoking related command/event/query handlers

Often we've logically connected set of behaviours and we want to keep them together, just like we do with [DDD Aggregates](https://martinfowler.com/bliki/DDD_Aggregate.html).
Yet framework may require us to create separate classes for each of the Handlers.   
This creates a lot of extra classes that are not business oriented, but oriented around technical limitations.
That blurs the vision of what parts of the system are naturally grouped together and what parts are not.

In case of Ecotone we `mark methods as Message Handlers` instead of `classes`, this way we can combine set of related handlers in one class.    
This leads to building business oriented classes which keeps cohesive parts together. 

### 3. Need for bringing whole framework structure and configuration

We often need to `obey to framework's structure and configuration`.  
This leads to situation when we need to maintain a lot of extra code and configs which can make upgrade painful in the long term.  
In case of having multiple applications this often leads to situation where services are working a bit differently which may lead to suprises in production.  
Upgrade of multiple applications is also a problem as it can lead to lock down on given version or slow it dramatically due to the cost.  

Take a look on current structure of the project, there is only your business logic and attributes.  
Ecotone `does not force you to use some structure or set of configuration files to bootstrap`.  
In general your project may only contains of business logic without or only small porition of framework related code.    
As a result this leads to smooth and easy upgrades and maintenance in your applications.
