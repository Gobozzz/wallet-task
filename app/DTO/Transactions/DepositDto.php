<?php

namespace App\DTO\Transactions;

readonly final class DepositDto
{
    public function __construct(
        public int|string $userId,
        public float $amount,
        public ?string $comment = null
    ) {
    }
}