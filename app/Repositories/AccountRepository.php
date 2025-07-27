<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Support\Collection;


class AccountRepository {


    public function getAllByUserId(int $userId): Collection
    {
        return Account::where('user_id', $userId)->get();
    }

    public function findById(int $id): Account
    {
        return Account::findOrFail($id);
    }

    public function create(array $data,int $userId): Account
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
        $account->delete();
    }
}