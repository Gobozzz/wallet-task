<?php

namespace App\DTO\Transactions;

readonly final class TransferDto
{

    public function __construct(
        public int|string $fromUserId,
        public int|string $toUserId,
        public float $amount,
        public ?string $comment = null
    ) {
    }
}