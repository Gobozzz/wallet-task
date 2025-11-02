<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TransferTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer(): void
    {
        $user_from = User::factory()->create();
        $user_to = User::factory()->create();

        $this->postJson('/api/v1/deposit', [
            'user_id' => $user_from->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertTrue($user_from->balance == 500);
        $this->assertTrue($user_to->balance == 0);

        $this->postJson('/api/v1/transfer', [
            'from_user_id' => $user_from->getKey(),
            'to_user_id' => $user_to->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertTrue($user_from->balance == 0);
        $this->assertTrue($user_to->balance == 500);
    }

    public function test_transfer_limit_request_minute(): void
    {
        $user_from = User::factory()->create();
        $user_to = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/transfer', [
                'from_user_id' => $user_from->getKey(),
                'to_user_id' => $user_to->getKey(),
                'amount' => 500.00,
            ]);
        }
        $this->postJson('/api/v1/transfer', [
            'from_user_id' => $user_from->getKey(),
            'to_user_id' => $user_to->getKey(),
            'amount' => 500.00,
        ])->assertStatus(429);
    }

    public function test_transfer_insufficient_fund(): void
    {
        $user_from = User::factory()->create();
        $user_to = User::factory()->create();

        $this->postJson('/api/v1/transfer', [
            'from_user_id' => $user_from->getKey(),
            'to_user_id' => $user_to->getKey(),
            'amount' => 500.00,
        ])->assertStatus(409)->assertJson([
                    "message" => __("Insufficient funds in the account")
                ]);
    }

    public function test_transfer_invalid_data(): void
    {
        $user_from = User::factory()->create();
        $user_to = User::factory()->create();

        $this->postJson('/api/v1/deposit', [
            'user_id' => $user_from->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->assertTrue($user_to->balance == 0);

        $this->postJson('/api/v1/transfer', [
            'from_user_id' => "qwerty",
            'to_user_id' => "qwerty",
            'amount' => 0.00,
        ])->assertStatus(422)->assertJsonStructure([
                    "message",
                    "errors" => ["from_user_id", "to_user_id", "amount"]
                ]);
    }



}
