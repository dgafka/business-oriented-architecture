<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Command;

final readonly class AddMoneyToWallet
{
    public function __construct(
        public string $walletId,
        public int $amount
    ) {
    }
}