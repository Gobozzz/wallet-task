<?php

namespace Tests\Feature;

use App\Enums\TransactionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepositTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deposit(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'comment' => 'Пополнение через карту'
        ]);

        $data = $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "user",
                    "to_user",
                    "type",
                    "amount",
                    "comment",
                    "balance"
                ],
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $data['data']['id'],
            'user_id' => $user->getKey(),
            'amount' => 500.00,
            'type' => TransactionType::DEPOSIT
        ]);
    }
    public function test_deposit_limit_request_minute(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/deposit', [
                'user_id' => $user->getKey(),
                'amount' => 500.00,
            ]);
        }
        $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(429);
    }

    public function test_deposit_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/deposit', [
            'user_id' => "qwerty",
            'amount' => 0.00,
        ]);

        $response->assertStatus(422)->assertJsonStructure([
            "message",
            "errors" => ["user_id", "amount"]
        ]);
    }

}
