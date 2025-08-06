<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Http\Requests\BudgetRequest;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;

class BudgetController extends Controller
{

    public function __construct(BudgetService $budgetService){
        $this->budgetService = $budgetService;
    }


    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $budgets = $this->budgetService->list($userId);

        return response()->json($budgets, 200);
    }

    public function store(BudgetRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $data = $request->validated();

        $budget = $this->budgetService->store($data, $userId);
        return response()->json($budget, 201);
    }

    public function show(string $budgetId, BudgetRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $budget = $this->budgetService->findById($budgetId, $userId);

        return response()->json($budget, 200);
    }


    public function update(BudgetRequest $request, Budget $budget): JsonResponse
    {
        $data = $request->validated();

        $budgetUpdated = $this->budgetService->update($budget, $data);

        return response()->json($budgetUpdated, 200);

    }

    public function destroy(BudgetRequest $request, Budget $budget): JsonResponse
    {
        $this->budgetService->delete($budget);

        return response()->noContent(204);
    }
}
