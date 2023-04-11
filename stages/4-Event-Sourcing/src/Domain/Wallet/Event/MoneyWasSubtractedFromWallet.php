<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Event;

final readonly class MoneyWasSubtractedFromWallet
{
    public function __construct(
        public string $walletId,
        public int $amount
    ) {
    }
}