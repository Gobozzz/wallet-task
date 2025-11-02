<?php

namespace App\Services;

use App\DTO\Transactions\DepositDto;
use App\DTO\Transactions\TransferDto;
use App\DTO\Transactions\WithdrawDto;
use App\Enums\TransactionType;
use App\Exceptions\ConflictException;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;

final class TransactionService
{
    public function __construct(
        private UserService $users
    ) {
    }

    public function deposit(DepositDto $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->findOrFail($data->userId);

            return Transaction::create([
                'user_id' => $user->getKey(),
                'type' => TransactionType::DEPOSIT,
                'amount' => $data->amount,
                'comment' => $data->comment,
            ]);
        });
    }

    public function withdraw(WithdrawDto $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->findOrFail($data->userId);

            if ($user->balance < $data->amount) {
                throw new ConflictException(__("Insufficient funds in the account"));
            }

            return Transaction::create([
                'user_id' => $user->getKey(),
                'type' => TransactionType::WITHDRAW,
                'amount' => $data->amount,
                'comment' => $data->comment,
            ]);
        });
    }

    public function transfer(TransferDto $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $fromUser = $this->users->findOrFail($data->fromUserId);
            $toUser = $this->users->findOrFail($data->toUserId);

            if ($fromUser->balance < $data->amount) {
                throw new ConflictException(__("Insufficient funds in the account"));
            }

            // Зачисление получателю
            Transaction::create([
                'user_id' => $toUser->getKey(),
                'type' => TransactionType::TRANSFER_IN,
                'amount' => $data->amount,
                'related_user_id' => $fromUser->getKey(),
                'comment' => $data->comment,
            ]);

            // Списание у отправителя
            return Transaction::create([
                'user_id' => $fromUser->getKey(),
                'type' => TransactionType::TRANSFER_OUT,
                'amount' => $data->amount,
                'related_user_id' => $toUser->getKey(),
                'comment' => $data->comment,
            ]);
        });
    }
}