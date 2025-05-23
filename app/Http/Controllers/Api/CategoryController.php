<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected CategoryService $categoryService
    ){}
    

    public function index(){
        return response()->json($this->categoryService->list(Auth::id()));
    }

    public function store(CategoryRequest $request){

        $data = $request->validated();

        $category = $this->categoryService->store($data, Auth::id());

        return response()->json($category, 201);
    }

    public function update(CategoryRequest $request, Category $category){


        $data = $request->validated();

        $category = $this->categoryService->update($category, $data);

        return response()->json($category);

}


    public function destroy(Category $category){

        $this->authorize('delete', $category);

        $this->categoryService->delete($category);

        return response()->json([], 204);

    }

}
