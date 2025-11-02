<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class BalanceController extends Controller
{

    public function getByUser(User $user): JsonResponse
    {
        return response()->json([
            'user_id' => $user->getKey(),
            'balance' => $user->balance,
        ]);
    }

}
