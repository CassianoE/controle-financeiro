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

    public function show(int $id){

        try {
            $category = $this->categoryService->findById($id);
    
            return response()->json([
                'message' => 'Categoria encontrada',
                'data' => $category
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria não encontrada',
            ], 404);
        }
    }

    public function update(CategoryRequest $request, int $id){

        try {
            $category = $this->categoryService->findById($id);
            $updatedCategory = $this->categoryService->update($category, $request->validated());
    
            return response()->json([
                'message' => 'Categoria atualizada com sucesso',
                'data' => $updatedCategory
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria não encontrada',
            ], 404);
        }
    }

    public function destroy(int $id){

        try {
            $category = $this->categoryService->findById($id);
            $this->authorize('delete', $category);
            $this->categoryService->delete($category);
    
            return response()->json([
                'message' => 'Categoria excluída com sucesso',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoria não encontrada',
            ], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'Você não tem permissão para excluir esta categoria.',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao excluir a categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
