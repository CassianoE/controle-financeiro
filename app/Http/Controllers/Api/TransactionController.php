<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function index()
    {
        $transactions = $this->transactionService->getAll();
        return response()->json(['data' => $transactions]);
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->create($request->validated());

        return response()->json(['data' => $transaction], 201);
    }

    public function show(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
    }

    public function destroy(int $id)
    {
        try {
            $this->transactionService->delete($id);
    
            return response()->json([
                'message' => 'Transação excluída com sucesso',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transação não encontrada',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao excluir a transação',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getByPeriod(Request $request)
    {
        $transactions = $this->transactionService->getbyPeriod(Auth::id(), $request->startDate, $request->endDate);

        return response()->json([
            'message' => 'Transações encontradas',
            'data' => $transactions
        ], 200);
    }

    public function getSummaryByPeriod(Request $request)
    {
        $summary = $this->transactionService->getSummaryByPeriod(Auth::id(), $request->startDate, $request->endDate);

        return response()->json([
            'message' => 'Resumo calculado com sucesso',
            'data' => $summary
        ], 200);
    }
    
}
