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

    public function findById (int $id,int $userId): Account
    {
        return $this->accountRepository->findById($id, $userId);
    }

    private function businessRules(array $data, ?Account $account = null): void
    {
        $type = $data['type'] ?? $account?->type;
        if($type instanceof AccountType){
            $type = $type->value;
        }
        $balance = $data['balance'] ?? $account?->balance;

        $incomingStatus = $data['status'] ?? null;
        if($incomingStatus instanceof AccountStatus){
            $incomingStatus = $incomingStatus->value;
        }

        $currentStatus = $account?->status;
        $status = $incomingStatus ??
            ($currentStatus instanceof AccountStatus ? $currentStatus->value : $currentStatus);

        if ($type !== AccountType::CREDIT->value && $balance < 0) {
            throw new NegativeBalanceNotAllowedException();
        }

        if(!in_array($status, AccountStatus::values(), true)){
            throw new InvalidAccountStatusException();
        }
    }

    public function store(array $data,int $userId): Account
    {
        $data['user_id'] = $userId;
        $this->businessRules($data);
        return $this->accountRepository->create($data);
    }

    public function update(Account $account,array $data): Account
    {
        $this->businessRules($data,$account);
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
