# Business focused architecture

Main tenet of [Ecotone Framework](https://docs.ecotone.tech/) is to allow developers focus on the business problems, not technical ones.      
When we can focus on the business we start deliver features much quicker and the solutions we produce are more accurate with what the business needs.   
Ecotone provides resilient Message-Driven architecture based on [EIP book](https://www.enterpriseintegrationpatterns.com/) similiar to [Spring Integration](https://spring.io/projects/spring-integration) which is foundation project for Netflix's [Spring Cloud](https://spring.io/projects/spring-cloud).  
And on top of that provide higher level abstraction to work with business flows and invariants using [DDD](https://www.domainlanguage.com/ddd/blue-book/) like methodology.

## Demo examples

This repository provides examples of how to use Ecotone to focus on the business part of the system.       
In `stages` catalog you will find different levels on which you can focus your architecture.  
Each stage contains of `README.md` file with description of what problems `Ecotone` solves, so you can focus on business problems.  

For the need of the description, given terminology is used:

* `Service` - This is your class registered in dependency injection container.
* `Application` - This your whole application which contains of all the necessary configuration to expose your business logic to the world
* `Aggregate` - This is your business logic which is grouped together, e.g. `Order` or `Customer`. Consider is as entity rich in behaviours.
* `Message Handler` - This is method of your class which is registered as message handler in messaging infrastructure, e.g. `Command/Event/Query Handler`.
* `Message` - This is message which is send to messaging infrastructure, e.g. `Command/Event/Query`.
* `Message Channel` - This is channel which is used to send messages to messaging infrastructure, e.g. Think of it as a transport layer.
* `Message Consumer` - This is consumer which is used to consume messages from `Message Channels` to execute your `Message Handlers`. Often called `"Worker"` in PHP world.

## Executing demos

To execute demo install dependencies using `composer install` and then run `php run_example.php`.  
In each stage you will also find related tests, to get a feeling of how you can test given level of architecture.  

## Using Symfony or Laravel

Demos are using [Ecotone Lite](https://docs.ecotone.tech/install-php-service-bus#install-ecotone-lite-no-framework), this is framework agnostic way to run Ecotone.
Yet Ecotone integrates with Symfony or Laravel, you can find installation steps in the [docs](https://docs.ecotone.tech/install-php-service-bus).

## More details

If you want to learn more about Ecotone and powerful concepts it provides, please visit [latest blog post](https://blog.ecotone.tech/building-reactive-message-driven-systems-in-php/), which describes the topic in details. 