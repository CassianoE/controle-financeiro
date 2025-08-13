<?php

namespace Tests\Unit\Services;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\User;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Services\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $accountRepositoryMock;
    protected $accountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountRepositoryMock = Mockery::mock(AccountRepositoryInterface::class);
        $this->accountService = new AccountService($this->accountRepositoryMock);
    }

    public function test_store_creates_account_successfully()
    {
        $user = User::factory()->create();
        $accountData = [
            'name' => 'Savings Account',
            'type' => 'savings',
            'balance' => 1000,
            'status' => AccountStatus::ACTIVE,
        ];

        $this->accountRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with(array_merge($accountData, ['user_id' => $user->id]))
            ->andReturn(new Account(array_merge($accountData, ['user_id' => $user->id])));

        $account = $this->accountService->store($accountData, $user->id);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($accountData['name'], $account->name);
    }

    public function test_store_throws_exception_for_negative_balance_on_non_credit_account()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo negativo só é permitido para contas do tipo crédito.');

        $user = User::factory()->create();
        $accountData = [
            'name' => 'Savings Account',
            'type' => 'savings',
            'balance' => -100,
            'status' => AccountStatus::ACTIVE,
        ];

        $this->accountService->store($accountData, $user->id);
    }

    public function test_store_throws_exception_for_invalid_status()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Status inválido. Valores permitidos: " . implode(", ", AccountStatus::values()));

        $user = User::factory()->create();
        $accountData = [
            'name' => 'Savings Account',
            'type' => 'savings',
            'balance' => 100,
            'status' => 'invalid_status',
        ];

        $this->accountService->store($accountData, $user->id);
    }

    public function test_update_updates_account_successfully()
    {
        $account = Account::factory()->create();
        $updateData = ['name' => 'New Name', 'balance' => 1500];

        $this->accountRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($account, $updateData)
            ->andReturn($account->fill($updateData));

        $updatedAccount = $this->accountService->update($account, $updateData);

        $this->assertEquals('New Name', $updatedAccount->name);
        $this->assertEquals(1500, $updatedAccount->balance);
    }

    public function test_update_throws_exception_for_negative_balance_on_non_credit_account()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo negativo só é permitido para contas do tipo crédito.');

        $account = Account::factory()->create(['type' => 'savings']);
        $updateData = ['balance' => -100];

        $this->accountService->update($account, $updateData);
    }

    public function test_update_throws_exception_for_invalid_status()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Status inválido. Valores permitidos: " . implode(", ", AccountStatus::values()));

        $account = Account::factory()->create();
        $updateData = ['status' => 'invalid_status'];

        $this->accountService->update($account, $updateData);
    }

    public function test_destroy_deletes_account_successfully()
    {
        $account = Mockery::mock(Account::class);
        $account->shouldReceive('transactions->exists')->andReturn(false);

        $this->accountRepositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($account)
            ->andReturn(true);

        $result = $this->accountService->destroy($account);

        $this->assertTrue($result);
    }

    public function test_destroy_throws_exception_if_account_has_transactions()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Conta não pode ser excluída, pois possui transações associadas.');

        $account = Mockery::mock(Account::class);
        $account->shouldReceive('transactions->exists')->andReturn(true);

        $this->accountService->destroy($account);
    }

    public function test_list_returns_accounts_for_user()
    {
        $user = User::factory()->create();
        $accounts = Account::factory()->count(2)->create(['user_id' => $user->id]);

        $this->accountRepositoryMock
            ->shouldReceive('getAllByUserId')
            ->once()
            ->with($user->id)
            ->andReturn($accounts);

        $result = $this->accountService->list($user->id);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_find_by_id_returns_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $this->accountRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($account->id, $user->id)
            ->andReturn($account);

        $result = $this->accountService->findById($account->id, $user->id);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals($account->id, $result->id);
    }
}
