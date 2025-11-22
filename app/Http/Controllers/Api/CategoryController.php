<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Policies\CategoryPolicy;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryPolicy $categoryPolicy
    ) {
    }


    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $accountId = $request->query("account_id");
        $categories = $this->categoryService->list($userId, $accountId);

        return response()->json($categories, 200);
    }

    public function store(CategoryCreateRequest $request)
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        $category = $this->categoryService->store($data, $userId);

        return response()->json([
            "data" => $category
        ],200);
    }

    public function show(Request $request, Category $category)
    {
        $this->authorize("view", $category);

        return response()->json([
            "data" => $category
        ], 200);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $this->authorize("update", $category);
        $data = $request->validated();

        $categoryUpdated = $this->categoryService->update($data, $category);

        return response()->json($categoryUpdated, 200);
    }

    public function destroy(Category $category)
    {
        $this->authorize("delete", $category);
        $this->categoryService->delete($category);

        return response()->json([
            "message" => "Categoria apagada com sucesso",
            "id" => $category->id
        ],200);
    }

}
