<?php

declare(strict_types=1);

namespace App\Domain\Wallet;

use App\Domain\Wallet\Command\AddMoneyToWallet;
use App\Domain\Wallet\Command\SubtractMoneyFromWallet;
use App\Domain\Wallet\Event\MoneyWasAddedToWallet;
use App\Domain\Wallet\Event\MoneyWasSubtractedFromWallet;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

#[EventSourcingAggregate]
final class Wallet
{
    use WithAggregateVersioning;

    #[AggregateIdentifier] public string $walletId;

    #[CommandHandler]
    public static function addFirstMoney(AddMoneyToWallet $command): array
    {
        return [new MoneyWasAddedToWallet($command->walletId, $command->amount)];
    }

    #[CommandHandler]
    public function addMoney(AddMoneyToWallet $command): array
    {
        return [new MoneyWasAddedToWallet($command->walletId, $command->amount)];
    }

    #[CommandHandler]
    public static function subtractFirstMoney(SubtractMoneyFromWallet $command): array
    {
        return [new MoneyWasSubtractedFromWallet($command->walletId, $command->amount)];
    }

    #[CommandHandler]
    public function subtractMoney(SubtractMoneyFromWallet $command): array
    {
        return [new MoneyWasSubtractedFromWallet($command->walletId, $command->amount)];
    }

    #[EventSourcingHandler]
    public function applyMoneyWasAddedToWallet(MoneyWasAddedToWallet|MoneyWasSubtractedFromWallet $event): void
    {
        $this->walletId = $event->walletId;
    }
}