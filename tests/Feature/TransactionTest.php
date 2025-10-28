<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_should_create_an_income_transaction()
    {
        $category = \App\Models\Category::factory()->create(['user_id' => $this->user->id, 'type' => 'income']);

        $transactionData = [
            'title' => 'Salário',
            'amount' => 5000,
            'date' => '2025-08-13',
            'type' => 'income',
            'category_id' => $category->id,
            'account_id' => $this->account->id,
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson('/api/transactions', $transactionData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'title' => 'Salário',
            'id' => $response->json('data.id')
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $this->account->id,
            'balance' => $this->account->balance + $transactionData['amount']
        ]);
    }

    public function test_should_create_an_expense_transaction()
    {
        $category = \App\Models\Category::factory()->create(['user_id' => $this->user->id, 'type' => 'expense']);

        $transactionData = [
            'title' => 'Aluguel',
            'amount' => 1200,
            'date' => '2025-08-13',
            'type' => 'expense',
            'category_id' => $category->id,
            'account_id' => $this->account->id,
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson('/api/transactions', $transactionData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('transactions', [
            'title' => 'Aluguel',
            'id' => $response->json('data.id')
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $this->account->id,
            'balance' => $this->account->balance - $transactionData['amount']
        ]);
    }

    public function test_should_delete_an_income_transaction_and_update_balance()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'type' => 'income',
            'amount' => 100
        ]);

        $this->account->balance += 100;
        $this->account->save();

        $response = $this->actingAs($this->user)->deleteJson('/api/transactions/' . $transaction->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseHas('accounts', [
            'id' => $this->account->id,
            'balance' => $this->account->balance - 100,
        ]);
    }

    public function test_should_delete_an_expense_transaction_and_update_balance()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
            'type' => 'expense',
            'amount' => 100
        ]);

        $this->account->balance -= 100;
        $this->account->save();

        $response = $this->actingAs($this->user)->deleteJson('/api/transactions/' . $transaction->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseHas('accounts', [
            'id' => $this->account->id,
            'balance' => $this->account->balance + 100,
        ]);
    }

    public function test_should_list_all_user_transactions()
    {
        Transaction::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'account_id' => $this->account->id,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/transactions');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }
}
