<?php

declare(strict_types=1);

namespace App\Domain\Notification;

use App\Domain\User\Event\UserWasRegistered;
use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Modelling\Attribute\EventHandler;

final class NotificationService
{
    #[Asynchronous("notificationChannel")]
    #[EventHandler(endpointId: "notificationService")]
    public function sendNotification(UserWasRegistered $event, Notifier $notifier): void
    {
        // in real scenario send notification by calling some external service over HTTP

        $notifier->sendFor($event->userId, sprintf("Notification to the user %s was sent. \n", $event->name->toString()));
    }
}