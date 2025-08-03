<?php

namespace App\Repositories;

use App\Models\Budget;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\BudgetRepositoryInterface;

class BudgetRepository implements BudgetRepositoryInterface
{

    public function getAllByUserId(int $userId): Collection
    {
        return Budget::where('user_id', $userId)->get();
    }

    public function findById(int $id, int $userId): Budget
    {
        return Budget::where('id', $id)
                      ->where('user_id', $userId)
                      ->firstOrFail();
    }

    public function create(array $data): Budget
    {
    return Budget::create($data);
    }

    public function update(Budget $budget, array $data): Budget
    {
        $budget->update($data);
        return $budget;
    }

    public function delete(Budget $budget): void
    {
        $budget->delete();
    }


}
