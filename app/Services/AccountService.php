<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Support\Collection;

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

    public function store(array $data,int $userId): Account
    {
        $data['user_id'] = $userId;
        return $this->accountRepository->create($data);
    }

    public function update(Account $account,array $data): Account
    {
        return $this->accountRepository->update($account,$data);
    }

    public function destroy(Account $account): bool
    {
        return $this->accountRepository->delete($account);
    }
}
