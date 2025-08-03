<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Support\Collection;


class AccountRepository implements AccountRepositoryInterface {


    public function getAllByUserId(int $userId): Collection
    {
        return Account::where('user_id', $userId)->get();
    }

    public function findById(int $id,int $userId): Account
    {
        return Account::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account,array $data): Account
    {
        $account->update($data);
        return $account;
    }

    public function delete(Account $account): bool
    {
        return $account->delete();
    }
}
