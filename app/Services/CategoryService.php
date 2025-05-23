<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryService
{

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function list (int $userId): Collection
    {
        return $this->categoryRepository->getAllByUserId($userId);
    }

    public function store (array $data, int $userId): Category
    {
        $data['user_id'] = $userId;
        return $this->categoryRepository->create($data);
    }

    public function findById(int $id): Category
    {
        return $this->categoryRepository->find($id);
    }

    public function show(int $id): Category
    {
        return $this->categoryRepository->show($id);
    }

    public function update(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }


    
}

