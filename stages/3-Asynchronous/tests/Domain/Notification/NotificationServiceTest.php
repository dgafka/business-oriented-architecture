<?php

declare(strict_types=1);

namespace Tests\App\Domain\User\Notification;

use App\Domain\Notification\NotificationService;
use App\Domain\Notification\Notifier;
use App\Domain\User\Event\UserWasRegistered;
use App\Domain\User\UserName;
use App\Infrastructure\Converter\UserNameConverter;
use App\Infrastructure\Converter\UuidConverter;
use App\Infrastructure\Notification\StubNotifier;
use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\Test\FlowTestSupport;
use Ecotone\Lite\Test\TestConfiguration;
use Ecotone\Messaging\Channel\SimpleMessageChannelBuilder;
use Ecotone\Messaging\Config\ModulePackageList;
use Ecotone\Messaging\Config\ServiceConfiguration;
use Ecotone\Messaging\Conversion\MediaType;
use Ecotone\Messaging\Endpoint\ExecutionPollingMetadata;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class NotificationServiceTest extends TestCase
{
    public function test_sending_notification_using_in_memory_asynchronous_channel(): void
    {
        $userId = Uuid::uuid4();
        $stubNotifier = new StubNotifier();
        $serviceConfiguration = ServiceConfiguration::createWithDefaults()
            ->withSkippedModulePackageNames(ModulePackageList::allPackagesExcept([ModulePackageList::ASYNCHRONOUS_PACKAGE]))
            ->withExtensionObjects([
                /** In Memory asynchronous Message Channel */
                SimpleMessageChannelBuilder::createQueueChannel("notificationChannel")
            ]);

        /** This event went asynchronous */
        $this->setupEcotoneLite([new NotificationService(), Notifier::class => $stubNotifier], $serviceConfiguration)
            ->publishEvent(new UserWasRegistered($userId, UserName::create('John')))
            /** Run Message Consumer to poll the message */
            ->run('notificationChannel', ExecutionPollingMetadata::createWithDefaults()->withTestingSetup());

        $this->assertCount(1, $stubNotifier->getNotificationFor($userId));
    }

    private function setupEcotoneLite(array $objects, ServiceConfiguration $serviceConfiguration): FlowTestSupport
    {
        return EcotoneLite::bootstrapFlowTesting(
            [NotificationService::class],
            $objects,
            $serviceConfiguration
        );
    }

    public function test_sending_notification_using_rabbitmq_asynchronous_channel(): void
    {
        $userId = Uuid::uuid4();
        $stubNotifier = new StubNotifier();
        $serviceConfiguration = ServiceConfiguration::createWithDefaults()
            /** Register all converters from given namespace */
            ->withNamespaces(['App\Infrastructure\Converter'])
            /** Enable asynchronous and conversion package */
            ->withSkippedModulePackageNames(ModulePackageList::allPackagesExcept([ModulePackageList::ASYNCHRONOUS_PACKAGE, ModulePackageList::JMS_CONVERTER_PACKAGE, ModulePackageList::AMQP_PACKAGE]))
            ->withExtensionObjects([
                /** Rabbitmq channel */
                AmqpBackedMessageChannelBuilder::create('notificationChannel'),
                TestConfiguration::createWithDefaults()
                    /** This will ensure that all asynchronous message are converted to json */
                    ->withMediaTypeConversion('notificationChannel', MediaType::createApplicationJson())
            ]);

        $this->setupEcotoneLite([new NotificationService(), Notifier::class => $stubNotifier, new UserNameConverter(), new UuidConverter(), AmqpConnectionFactory::class => new AmqpConnectionFactory(['dsn' => getenv('RABBIT_DSN') ? getenv('RABBIT_DSN') : 'amqp://guest:guest@localhost:5672/%2f'])], $serviceConfiguration)
            ->publishEvent(new UserWasRegistered($userId, UserName::create('John')))
            /** Run message consumer for one message */
            ->run('notificationChannel', ExecutionPollingMetadata::createWithDefaults()->withTestingSetup());

        $this->assertCount(1, $stubNotifier->getNotificationFor($userId));
    }
}