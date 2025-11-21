<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{

    public function getAll(int $userId, ?int $accountId = null): Collection
    {
        $query = Category::query()
        ->where("user_id", $userId);

        if($accountId){
            $query->where("account_id", $accountId);
        }
        
        return $query->get();
    }

    public function findById(int $id): Category
    {
        return Category::findOrFail($id);
    }


    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function show(int $id): Category
    {
        return Category::findOrFail($id);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }


}
