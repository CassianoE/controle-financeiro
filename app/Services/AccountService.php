<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Support\Collection;

class AccountService {

    public function __construct(AccountRepositoryInterface $AccountRepository){
        $this->AccountRepository = $AccountRepository;
    }

    public function list(int $id): Collection
    {
        return $this->AccountRepository->getAllByUserId($id);
    }

    public function findById (int $id): Account
    {
        return $this->AccountRepository->findById($id);
    }

    public function store(array $data,int $userId): Account
    {
        $data['user_id'] = $userId;
        return $this->AccountRepository->create($data);
    }

    public function update(Account $account,array $data): Account
    {
        return $this->AccountRepository->update($account,$data);
    }

    public function delete(Account $account): bool
    {
        return $this->AccountRepository->delete($account);
    }
}