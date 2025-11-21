<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Support\Collection;

interface AccountRepositoryInterface {

    public function getAllByUserId(int $userId): Collection;

    public function create(array $data): Account;

    public function update(Account $account,array $data): Account;

    public function delete(Account $account): bool;

    public function findById(int $id): Account;
}
