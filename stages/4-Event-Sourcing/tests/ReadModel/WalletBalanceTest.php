<?php

declare(strict_types=1);

namespace Tests\App\ReadModel;

use App\Domain\Wallet\Event\MoneyWasAddedToWallet;
use App\Domain\Wallet\Event\MoneyWasSubtractedFromWallet;
use App\Domain\Wallet\Wallet;
use App\ReadModel\WalletBalance;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Store\Document\InMemoryDocumentStore;
use PHPUnit\Framework\TestCase;

final class WalletBalanceTest extends TestCase
{
    public function test_adding_money_to_wallet()
    {
        $walletId = "123";

        $this->assertEquals(
            1000,
            EcotoneLite::bootstrapFlowTestingWithEventStore([WalletBalance::class], [new WalletBalance(InMemoryDocumentStore::createEmpty())])
                ->withEventStream(Wallet::class, [
                    new MoneyWasAddedToWallet($walletId, 1000)
                ])
                ->triggerProjection("wallet_balance")
                ->sendQueryWithRouting("getBalance", $walletId)
        );
    }

    public function test_adding_and_subtracting_money_to_wallet()
    {
        $walletId = '123';

        $this->assertEquals(
            700,
            EcotoneLite::bootstrapFlowTestingWithEventStore([WalletBalance::class], [new WalletBalance(InMemoryDocumentStore::createEmpty())])
                ->withEventStream(Wallet::class, [
                    new MoneyWasAddedToWallet($walletId, 1000),
                    new MoneyWasSubtractedFromWallet($walletId, 300)
                ])
                ->triggerProjection('wallet_balance')
                ->sendQueryWithRouting('getBalance', $walletId)
        );
    }
}