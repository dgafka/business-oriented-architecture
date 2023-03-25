<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Domain\Notification\Notifier;
use Ramsey\Uuid\UuidInterface;

final class StubNotifier implements Notifier
{
    /** @var array<string, array<string>> */
    private array $notifications = [];

    public function sendFor(UuidInterface $userId, string $message): void
    {
        $this->notifications[$userId->toString()][] = $message;
    }

    /**
     * @return array<string>
     */
    public function getNotificationFor(UuidInterface $userId): array
    {
        return $this->notifications[$userId->toString()] ?? [];
    }
}