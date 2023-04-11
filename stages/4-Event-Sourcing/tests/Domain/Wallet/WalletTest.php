<?php

declare(strict_types=1);

namespace Tests\App\Domain\Wallet;

use App\Domain\Wallet\Command\AddMoneyToWallet;
use App\Domain\Wallet\Command\SubtractMoneyFromWallet;
use App\Domain\Wallet\Event\MoneyWasAddedToWallet;
use App\Domain\Wallet\Event\MoneyWasSubtractedFromWallet;
use App\Domain\Wallet\Wallet;
use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\TestCase;

final class WalletTest extends TestCase
{
    public function test_adding_money_to_wallet()
    {
        $this->assertEquals(
            [new MoneyWasAddedToWallet('123', 1000)],
            EcotoneLite::bootstrapFlowTesting([Wallet::class])
                ->sendCommand(new AddMoneyToWallet('123', 1000))
                ->getRecordedEvents()
        );
    }

    public function test_subtracting_money_to_wallet()
    {
        $this->assertEquals(
            [new MoneyWasSubtractedFromWallet('123', 100)],
            EcotoneLite::bootstrapFlowTesting([Wallet::class])
                ->sendCommand(new SubtractMoneyFromWallet('123', 100))
                ->getRecordedEvents()
        );
    }
}