<?php

namespace App\Repositories\Contracts;

use App\Models\Budget;
use Illuminate\Support\Collection;

interface BudgetRepositoryInterface
{
    public function getAllByUserId(int $userId): Collection;

    public function findById(int $id, int $userId): Budget;

    public function create(array $data): Budget;

    public function update(Budget $budget, array $data): Budget;

    public function delete(Budget $budget): bool;
}
