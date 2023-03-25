<?php

declare(strict_types=1);

namespace Tests\App\Domain\User\Notification;

use App\Domain\Notification\NotificationService;
use App\Domain\Notification\Notifier;
use App\Domain\User\Event\UserWasRegistered;
use App\Domain\User\UserName;
use App\Infrastructure\Notification\StubNotifier;
use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class NotificationServiceTest extends TestCase
{
    public function test_sending_notification(): void
    {
        $stubNotifier = new StubNotifier();
        $ecotoneLite = EcotoneLite::bootstrapFlowTesting(
            [NotificationService::class],
            [new NotificationService(), Notifier::class => $stubNotifier]
        );

        $userId = Uuid::uuid4();
        $ecotoneLite->publishEvent(new UserWasRegistered($userId, UserName::create('John')));

        $this->assertCount(1, $stubNotifier->getNotificationFor($userId));
    }
}