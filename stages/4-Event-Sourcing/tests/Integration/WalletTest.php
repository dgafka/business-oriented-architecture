<?php

declare(strict_types=1);

namespace Tests\App\Integration;

use App\Domain\Wallet\Command\AddMoneyToWallet;
use App\Domain\Wallet\Command\SubtractMoneyFromWallet;
use App\Domain\Wallet\Event\MoneyWasAddedToWallet;
use App\Domain\Wallet\Event\MoneyWasSubtractedFromWallet;
use App\Domain\Wallet\Wallet;
use App\ReadModel\WalletBalance;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Store\Document\InMemoryDocumentStore;
use PHPUnit\Framework\TestCase;

final class WalletTest extends TestCase
{
    public function test_adding_and_subtracting_money_from_wallet()
    {
        $walletId = '123';

        $this->assertEquals(
            300,
            EcotoneLite::bootstrapFlowTestingWithEventStore([Wallet::class, WalletBalance::class], [new WalletBalance(InMemoryDocumentStore::createEmpty())])
                ->sendCommand(new AddMoneyToWallet($walletId, 1000))
                ->sendCommand(new SubtractMoneyFromWallet($walletId, 200))
                ->sendCommand(new SubtractMoneyFromWallet($walletId, 500))
                ->sendQueryWithRouting('getBalance', $walletId)
        );
    }
}