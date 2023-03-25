<?php

declare(strict_types=1);

namespace App\Domain\Notification;

use Ramsey\Uuid\UuidInterface;

interface Notifier
{
    public function sendFor(UuidInterface $userId, string $message): void;
}