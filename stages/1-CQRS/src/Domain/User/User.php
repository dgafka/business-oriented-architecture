<?php

declare(strict_types=1);

namespace App\Domain\User;

use Ramsey\Uuid\UuidInterface;

final class User
{
    private function __construct(
        private readonly UuidInterface $userId,
        private UserName               $name,
    )
    {
    }

    public static function create(UuidInterface $userId, UserName $name): self
    {
        return new self($userId, $name);
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function changeName(UserName $name): void
    {
        $this->name = $name;
    }
}