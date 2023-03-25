<?php

declare(strict_types=1);

namespace Tests\App\Domain\User\UserName;

use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Application\UserService;
use App\Domain\User\User;
use App\Domain\User\UserName;
use App\Domain\User\UserRepository;
use App\Infrastructure\InMemoryUserRepository;
use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends TestCase
{
    public function test_registering_new_user(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [User::class, InMemoryUserRepository::class],
            [
                new InMemoryUserRepository()
            ]
        );

        $userId = Uuid::uuid4();
        $userName = UserName::create('John Doe');
        $this->assertEquals(
            $userName,
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, $userName))
                ->sendQueryWithRouting('user.getName', metadata: ['aggregate.id' => $userId])
        );
    }

    public function test_changing_user_name(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [User::class, InMemoryUserRepository::class],
            [
                new InMemoryUserRepository()
            ]
        );

        $userId = Uuid::uuid4();
        $this->assertEquals(
            UserName::create('RabbitMQ'),
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, UserName::create('John Doe')))
                ->sendCommand(new ChangeUserName($userId, UserName::create('RabbitMQ')))
                ->sendQueryWithRouting('user.getName', metadata: ['aggregate.id' => $userId])
        );
    }

    public function test_throwing_exception_when_changing_name_for_blocker_user(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [User::class, InMemoryUserRepository::class],
            [
                new InMemoryUserRepository()
            ]
        );

        $this->expectException(\RunTimeException::class);

        $userId = Uuid::uuid4();
        $ecotoneLite
            ->sendCommand(new RegisterUser($userId, UserName::create('John Doe')))
            ->sendCommandWithRoutingKey('user.block', metadata: ['aggregate.id' => $userId])
            ->sendCommand(new ChangeUserName($userId, UserName::create('RabbitMQ')));
    }
}