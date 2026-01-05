<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Http\Requests\BudgetCreateRequest;
use App\Http\Requests\BudgetUpdateRequest;
use App\Services\BudgetService;
use App\Policies\BudgetPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BudgetController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected BudgetService $budgetService,
        protected BudgetPolicy $budgetPolicy
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $budgets = $this->budgetService->list($userId);

        return response()->json($budgets, 200);
    }

    public function store(BudgetCreateRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $data = $request->validated();

        $budget = $this->budgetService->store($data, $userId);
        return response()->json($budget, 201);
    }

    public function show(Request $request, Budget $budget): JsonResponse
    {
        $this->authorize('view', $budget);

        return response()->json($budget, 200);
    }

    public function update(BudgetUpdateRequest $request, Budget $budget): JsonResponse
    {
        $this->authorize('update', $budget);
        $data = $request->validated();

        $budgetUpdated = $this->budgetService->update($budget, $data);

        return response()->json($budgetUpdated, 200);
    }

    public function destroy(Request $request, Budget $budget): JsonResponse
    {
        $this->authorize('delete', $budget);
        $this->budgetService->delete($budget);

        return response()->noContent();
    }
}
