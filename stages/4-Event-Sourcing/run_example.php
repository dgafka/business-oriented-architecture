<?php

use App\Domain\Notification\Notifier;
use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Domain\User\UserName;
use App\Domain\User\UserRepository;
use App\Domain\Wallet\Command\AddMoneyToWallet;
use App\Domain\Wallet\Command\SubtractMoneyFromWallet;
use App\Infrastructure\InMemoryUserRepository;
use App\Infrastructure\Notification\EchoNotifier;
use Ecotone\Lite\EcotoneLiteApplication;
use Ecotone\Messaging\Endpoint\ExecutionPollingMetadata;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\Dbal\DbalConnectionFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/vendor/autoload.php';

$ecotoneLite = EcotoneLiteApplication::bootstrap(pathToRootCatalog: __DIR__, classesToRegister: [DbalConnectionFactory::class => new DbalConnectionFactory(['dsn' => getenv('DATABASE_DSN') ? getenv('DATABASE_DSN') : 'pgsql://ecotone:secret@localhost:5432/ecotone'])]);

/** In case of using Symfony or Laravel, Buses will be automatically available in your dependency container */
$commandBus = $ecotoneLite->getCommandBus();
$queryBus = $ecotoneLite->getQueryBus();

$walletId = Uuid::uuid4()->toString();

/** This works on real database implementation. You may look in to db for more details */
$commandBus->send(new AddMoneyToWallet($walletId, 1000));
$commandBus->send(new SubtractMoneyFromWallet($walletId, 300));

TestCase::assertEquals(
    700,
    $queryBus->sendWithRouting('getBalance', $walletId)
);

echo "Calculations are correct\n\n";