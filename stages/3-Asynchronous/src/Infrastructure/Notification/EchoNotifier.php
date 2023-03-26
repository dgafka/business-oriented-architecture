<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Domain\Notification\Notifier;
use Ramsey\Uuid\UuidInterface;

final class EchoNotifier implements Notifier
{
    public function sendFor(UuidInterface $userId, string $message): void
    {
        echo $message;
    }
}