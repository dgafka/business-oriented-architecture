<?php

use App\Domain\Notification\Notifier;
use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Domain\User\UserName;
use App\Domain\User\UserRepository;
use App\Infrastructure\InMemoryUserRepository;
use App\Infrastructure\Notification\EchoNotifier;
use Ecotone\Lite\EcotoneLiteApplication;
use Ecotone\Messaging\Endpoint\ExecutionPollingMetadata;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/vendor/autoload.php';

$ecotoneLite = EcotoneLiteApplication::bootstrap(pathToRootCatalog: __DIR__, classesToRegister: [UserRepository::class => new InMemoryUserRepository(), Notifier::class => new EchoNotifier(), AmqpConnectionFactory::class => new AmqpConnectionFactory(['dsn' => getenv('RABBIT_DSN') ? getenv('RABBIT_DSN') : 'amqp://guest:guest@localhost:5672/%2f'])]);

/** In case of using Symfony or Laravel, Buses will be automatically available in your dependency container */
$commandBus = $ecotoneLite->getCommandBus();
$queryBus = $ecotoneLite->getQueryBus();

$userId = Uuid::uuid4();
$expectedName = UserName::create('John Doe');

echo "Starting example. \n";
$commandBus->send(new RegisterUser($userId, UserName::create('John Snow')));
$commandBus->send(new ChangeUserName($userId, $expectedName));

/** Running Message Consumer for notificationChannel using RabbitMQ */
$ecotoneLite->run("notificationChannel", ExecutionPollingMetadata::createWithDefaults()->withTestingSetup());

Assert::assertEquals(
    $expectedName,
    $queryBus->sendWithRouting('user.getName', metadata: ['aggregate.id' => $userId])
);
echo "Example finished with success.\n";