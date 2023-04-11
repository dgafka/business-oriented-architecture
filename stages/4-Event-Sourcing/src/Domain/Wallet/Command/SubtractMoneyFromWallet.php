<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Command;

final readonly class SubtractMoneyFromWallet
{
    public function __construct(
        public string $walletId,
        public int $amount
    ) {
    }
}