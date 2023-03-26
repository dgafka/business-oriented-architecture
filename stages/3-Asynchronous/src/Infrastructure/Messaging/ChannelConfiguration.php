<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging;

use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Messaging\Attribute\ServiceContext;

final class ChannelConfiguration
{
    #[ServiceContext]
    public function rabbitMQMessageChannel()
    {
        return AmqpBackedMessageChannelBuilder::create("notificationChannel");
    }
}