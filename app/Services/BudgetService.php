<?php

namespace App\Services;

use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Models\Budget;
use Illuminate\Support\Collection;

class BudgetService
{

    public function __construct(
        protected BudgetRepositoryInterface $budgetRepository,
    ) {}

    public function list(int $userId): Collection
    {
        return $this->budgetRepository->getAllByUserId($userId);
    }

    public function findById (int $id, int $userId): Budget
    {
        return $this->budgetRepository->findById($id, $userId);
    }

    public function store(array $data, int $userId): Budget
    {
        $data['user_id'] = $userId;
        return $this->budgetRepository->create($data);
    }

    public function update(Budget $budget,array $data): Budget
    {
        return $this->budgetRepository->update($budget,$data);
    }

    public function delete(Budget $budget): bool
    {
        return $this->budgetRepository->delete($budget);
    }




}
