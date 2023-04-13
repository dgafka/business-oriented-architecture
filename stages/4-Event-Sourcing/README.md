# 4. Event Sourcing

Introduce Basket Event Sourcing Aggregate.   

## Solved Common Problems

### 1. Serializing and Deserializing Events  

Events when stored to Event Store need to be serialized and when consumed from Event Store they need to be deserialized.
Serializing and deserializing are not business related problems and takes our focus out of domain logic.   

Ecotone provides automatic conversion for our `Message classes`, so we don't need to deal with it manually.    

### 2. Setting up Event Store

Ecotone implements Event Store that work out of the box with PostgreSQL, MariaDB and MySQL.  
This way we can focus directly on business logic and infrastructure is taken care of. 
It's really easy to introduce new Event Sourced Aggregates thanks to that. 

### 3. Projections

If we are using default Ecotone's Event Store, projections can read from that.  
Projections are read models that are updated by events.  
All we need to configure is how the events should be used to construct the read model.  
If we want we can abstract away projection's storage using `Document Store`.
