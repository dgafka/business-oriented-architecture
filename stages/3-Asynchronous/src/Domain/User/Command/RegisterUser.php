<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

use App\Domain\User\UserName;
use Ramsey\Uuid\UuidInterface;

final class RegisterUser
{
    public function __construct(
        public readonly UuidInterface $userId,
        public readonly UserName $name,
    ) {
    }
}