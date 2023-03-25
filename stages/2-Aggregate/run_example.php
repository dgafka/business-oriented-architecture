<?php

use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Domain\User\UserName;
use App\Domain\User\UserRepository;
use App\Infrastructure\InMemoryUserRepository;
use Ecotone\Lite\EcotoneLiteApplication;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/vendor/autoload.php';

$ecotoneLite = EcotoneLiteApplication::bootstrap(pathToRootCatalog: __DIR__, classesToRegister: [UserRepository::class => new InMemoryUserRepository()]);

/** In case of using Symfony or Laravel, Buses will be automatically available in your dependency container */
$commandBus = $ecotoneLite->getCommandBus();
$queryBus = $ecotoneLite->getQueryBus();

$userId = Uuid::uuid4();
$expectedName = UserName::create('John Doe');

echo "Starting example. \n";
$commandBus->send(new RegisterUser($userId, UserName::create('John Snow')));
$commandBus->send(new ChangeUserName($userId, $expectedName));

Assert::assertEquals(
    $expectedName,
    $queryBus->sendWithRouting('user.getName', metadata: ['aggregate.id' => $userId])
);
echo "Example finished with success.\n";