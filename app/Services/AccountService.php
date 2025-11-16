<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Support\Collection;
use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Exceptions\NegativeBalanceNotAllowedException;
use App\Exceptions\InvalidAccountStatusException;
use App\Exceptions\AccountHasTransactionsException;

class AccountService {

    public function __construct(
        protected AccountRepositoryInterface $accountRepository
    ) {}

    public function list(int $id): Collection
    {
        return $this->accountRepository->getAllByUserId($id);
    }

    public function store(array $data,int $userId): Account
    {
        if($data["type"] !== "credit" && $data["balance"] < 0){
            throw new NegativeBalanceNotAllowedException();
        }
        
        $data["user_id"] = $userId;

        return $this->accountRepository->create($data);
    }

    public function update(Account $account,array $data): Account
    {
        $typeFinal = $data['type'] ?? $account->type;
        $balanceFinal = $data['balance'] ?? $account->balance;

        if($typeFinal !== "credit" && $balanceFinal < 0){
            throw new NegativeBalanceNotAllowedException();
        }

        return $this->accountRepository->update($account,$data);
    }

    public function destroy(Account $account): bool
    {

        if ($account->transactions()->exists()) {
            throw new AccountHasTransactionsException();
        }
        return $this->accountRepository->delete($account);
    }
}
