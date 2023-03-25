<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Ramsey\Uuid\UuidInterface;

final class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->getUserId()->toString()] = $user;
    }

    public function find(UuidInterface $userId): ?User
    {
        return $this->users[$userId->toString()] ?? null;
    }
}