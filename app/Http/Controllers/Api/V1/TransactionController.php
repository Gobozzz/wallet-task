<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Transaction\DepositRequest;
use App\Http\Requests\Api\V1\Transaction\TransferRequest;
use App\Http\Requests\Api\V1\Transaction\WithdrawRequest;
use App\Http\Resources\Api\V1\BaseTransactionResource;
use App\Services\TransactionService;

final class TransactionController extends Controller
{

    public function __construct(
        private TransactionService $transactions
    ) {
    }

    public function deposit(DepositRequest $request): BaseTransactionResource
    {
        $data = $request->getDto();
        $transaction = $this->transactions->deposit($data);
        return new BaseTransactionResource($transaction);
    }
    public function withdraw(WithdrawRequest $request): BaseTransactionResource
    {
        $data = $request->getDto();
        $transaction = $this->transactions->withdraw($data);
        return new BaseTransactionResource($transaction);
    }
    public function transfer(TransferRequest $request): BaseTransactionResource
    {
        $data = $request->getDto();
        $transaction = $this->transactions->transfer($data);
        return new BaseTransactionResource($transaction);
    }

}
