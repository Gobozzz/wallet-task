<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_balance_empty(): void
    {
        $user = User::factory()->create();
        $this->get("/api/v1/balance/{$user->getKey()}")->assertStatus(200)->assertJson([
            'user_id' => $user->getKey(),
            'balance' => 0
        ]);
    }

    public function test_get_balance_not_empty(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/deposit', [
            'user_id' => $user->getKey(),
            'amount' => 500.00,
        ])->assertStatus(201);

        $this->get("/api/v1/balance/{$user->getKey()}")->assertStatus(200)->assertJson([
            'user_id' => $user->getKey(),
            'balance' => 500.00
        ]);
    }

}
