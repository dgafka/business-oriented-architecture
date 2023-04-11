<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\Domain\Wallet\Event\MoneyWasAddedToWallet;
use App\Domain\Wallet\Event\MoneyWasSubtractedFromWallet;
use App\Domain\Wallet\Wallet;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Messaging\Store\Document\DocumentStore;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;

/**
 * Example using Document Store, which can be in memory or dbal.
 * We could use in here direct connection and #[ProjectionInitialization] for creating table
 */
#[Projection("wallet_balance", Wallet::class)]
final class WalletBalance
{
    public function __construct(private readonly DocumentStore $documentStore) {}

    #[EventHandler]
    public function addMoney(MoneyWasAddedToWallet $event): void
    {
        $balance = $this->documentStore->findDocument("wallet_balance", $event->walletId);

        if ($balance === null) {
            $this->documentStore->addDocument(
                "wallet_balance",
                $event->walletId,
                [
                    "balance" => $event->amount
                ]
            );

            return;
        }

        $this->documentStore->updateDocument(
            "wallet_balance",
            $event->walletId,
            [
                "balance" => $balance["balance"] + $event->amount
            ]
        );
    }

    #[EventHandler]
    public function subtractMoney(MoneyWasSubtractedFromWallet $event): void
    {
        $balance = $this->documentStore->findDocument("wallet_balance", $event->walletId);

        if ($balance === null) {
            $this->documentStore->addDocument(
                "wallet_balance",
                $event->walletId,
                [
                    "balance" => -$event->amount
                ]
            );

            return;
        }

        $this->documentStore->updateDocument(
            "wallet_balance",
            $event->walletId,
            [
                "balance" => $balance["balance"] - $event->amount
            ]
        );
    }

    #[QueryHandler("getBalance")]
    public function getBalance(string $walletId): int
    {
        $wallet = $this->documentStore->findDocument("wallet_balance", $walletId);
        if ($wallet === null) {
            return 0;
        }

        return $wallet["balance"];
    }

    #[ProjectionReset]
    public function reset()
    {
        $this->documentStore->dropCollection("wallet_balance");
    }
}