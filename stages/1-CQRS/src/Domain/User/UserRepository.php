<?php

declare(strict_types=1);

namespace App\Domain\User;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function save(User $user): void;

    public function find(UuidInterface $userId): ?User;
}