<?php

namespace App\DTO\Transactions;

readonly final class WithdrawDto
{
    public function __construct(
        public int|string $userId,
        public float $amount,
        public ?string $comment = null
    ) {
    }
}