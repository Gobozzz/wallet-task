<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\TransactionType;

class WithdrawTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_withdraw(): void
    {
        $user = User::factory()->create();

        $data_deposit = $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'id' => $data_deposit['data']['id'],
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'type' => TransactionType::DEPOSIT
        ]);

        $data_withdraw = $this->postJson('/api/v1/withdraw', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'id' => $data_withdraw['data']['id'],
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'type' => TransactionType::WITHDRAW
        ]);
    }

    public function test_withdraw_limit_request_minute(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/withdraw', [
                'user_id' => $user->getKey(),
                'amount' => 500.00,
            ]);
        }
        $this->postJson('/api/v1/withdraw', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(429);
    }

    public function test_withdraw_invalid_data(): void
    {
        $user = User::factory()->create();

        $data_deposit = $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'id' => $data_deposit['data']['id'],
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'type' => TransactionType::DEPOSIT
        ]);

        $this->postJson('/api/v1/withdraw', [
            'user_id' => "qwerty",
            'amount' => 0.00,
        ])->assertStatus(422)->assertJsonStructure([
                    "message",
                    "errors" => ["user_id", "amount"]
                ]);
    }



    public function test_withdraw_insufficient_fund(): void
    {
        $user = User::factory()->create();

        $data_deposit = $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'id' => $data_deposit['data']['id'],
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'type' => TransactionType::DEPOSIT
        ]);

        $this->postJson('/api/v1/withdraw', [
            'user_id' => $user->getKey(),
            'amount' => 500.10,
        ])->assertStatus(409)->assertJson([
                    "message" => __("Insufficient funds in the account")
                ]);
    }

}
