<?php

namespace App\Services;

use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\Budget;
use Illuminate\Support\Collection;
use App\Exceptions\UnauthorizedCategoryAccessException;

class BudgetService
{

    public function __construct(
        protected BudgetRepositoryInterface $budgetRepository,
        protected CategoryRepositoryInterface $categoryRepository,
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
        $category = $this->categoryRepository->findById($data['category_id']);

        if ($category->user_id !== $userId) {
            throw new UnauthorizedCategoryAccessException();
        }

        $data['user_id'] = $userId;
        return $this->budgetRepository->create($data);
    }

    public function update(Budget $budget, array $data): Budget
    {
        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->findById($data['category_id']);

            if ($category->user_id !== $budget->user_id) {
                throw new UnauthorizedCategoryAccessException();
            }
        }

        return $this->budgetRepository->update($budget, $data);
    }

    public function delete(Budget $budget): bool
    {
        return $this->budgetRepository->delete($budget);
    }




}
