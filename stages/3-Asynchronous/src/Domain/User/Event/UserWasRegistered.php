<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\UserName;
use Ramsey\Uuid\UuidInterface;

final class UserWasRegistered
{
    public function __construct(
        public readonly UuidInterface $userId,
        public readonly UserName $name,
    ) {
    }
}