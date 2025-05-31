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
    public function queryByPeriod(int $userId, ?String $startDate, ?String $endDate): Collection;
    public function getbyPeriod(int $userId, ?String $startDate, ?String $endDate): Collection;
    public function getSummaryByPeriod(int $userId, ?String $startDate, ?String $endDate): array;
    
}
