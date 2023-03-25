<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Command\ChangeUserName;
use App\Application\Command\RegisterUser;
use App\Domain\User\User;
use App\Domain\User\UserName;
use App\Domain\User\UserRepository;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ramsey\Uuid\UuidInterface;

final class UserService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    #[CommandHandler]
    public function registerUser(RegisterUser $command): void
    {
        $user = User::create($command->userId, $command->name);
        $this->userRepository->save($user);
    }

    #[CommandHandler]
    public function changeUserName(ChangeUserName $command): void
    {
        $user = $this->userRepository->find($command->userId);
        $user->changeName($command->name);
        $this->userRepository->save($user);
    }

    #[QueryHandler('user.getName')]
    public function getUserName(UuidInterface $userId): UserName
    {
        $user = $this->userRepository->find($userId);

        return $user->getName();
    }
}