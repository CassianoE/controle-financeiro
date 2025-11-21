<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function getAll($userId, ?int $accountId = null): Collection;
    public function findById(int $id): Transaction;
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(Transaction $transaction);
    public function queryByPeriod(int $userId, ?String $startDate, ?String $endDate): Collection;
    public function getbyPeriod(int $userId, ?String $startDate, ?String $endDate): Collection;
    public function getSummaryByPeriod(int $userId, ?String $startDate, ?String $endDate): array;
    
}
