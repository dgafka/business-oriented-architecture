<?php

declare(strict_types=1);

namespace App\Domain\Notification;

use App\Domain\User\Event\UserWasRegistered;
use Ecotone\Modelling\Attribute\EventHandler;

final class NotificationService
{
    #[EventHandler]
    public function sendNotification(UserWasRegistered $event): void
    {
        // send notification by calling some external service over HTTP

        echo sprintf("Notification to the user %s was sent. \n", $event->name->toString());
    }
}