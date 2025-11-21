<?php

namespace App\Services;

use App\DTOs\CreateCategoryDTO;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryService
{

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    public function list(int $userId, ?int $accountId = null): Collection
    {
        return $this->categoryRepository->getAll($userId, $accountId);
    }

    public function store(array $data, int $userId): Category
    {
        $categoryDTO = CreateCategoryDTO::fromArray($data);

        return $this->categoryRepository->create([
            ...$categoryDTO->toArray(),
            "user_id" => $userId
        ]);
    }

    public function findById(int $id): Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function show(int $id): Category
    {
        return $this->categoryRepository->show($id);
    }

    public function update(array $data, Category $category): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }



}

