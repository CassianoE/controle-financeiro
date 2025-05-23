<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getBalance(int $userId): float;
}
