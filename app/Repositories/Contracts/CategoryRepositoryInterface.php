<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAllByUserId(int $userId): Collection;

    public function find(int $id): Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): void;

    public function show(int $id): Category;

    public function findById(int $id): Category;
}

