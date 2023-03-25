<?php

declare(strict_types=1);

namespace Tests\App\Application;

use App\Application\Command\ChangeUserName;
use App\Application\Command\RegisterUser;
use App\Application\UserService;
use App\Domain\User\UserName;
use App\Infrastructure\InMemoryUserRepository;
use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserServiceTest extends TestCase
{
    public function test_registering_new_user(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [UserService::class],
            [
                new UserService(new InMemoryUserRepository())
            ]
        );

        $userId = Uuid::uuid4();
        $userName = UserName::create('John Doe');
        $this->assertEquals(
            $userName,
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, $userName))
                ->sendQueryWithRouting('user.getName', $userId)
        );
    }

    public function test_changing_user_name(): void
    {
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [UserService::class],
            [
                new UserService(new InMemoryUserRepository())
            ]
        );

        $userId = Uuid::uuid4();
        $this->assertEquals(
            UserName::create('RabbitMQ'),
            $ecotoneLite
                ->sendCommand(new RegisterUser($userId, UserName::create('John Doe')))
                ->sendCommand(new ChangeUserName($userId, UserName::create('RabbitMQ')))
                ->sendQueryWithRouting('user.getName', $userId)
        );
    }
}