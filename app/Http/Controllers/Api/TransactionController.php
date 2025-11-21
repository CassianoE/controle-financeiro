<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionCreateRequest;
use App\Http\Requests\TransactionUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
       $userId = $request->user()->id;
       $accountId = $request->query("account_id");
       $categoryId = $request->query("category_id");

       $transactions = $this->transactionService->getAll($userId, $accountId, $categoryId);

       return response()->json($transactions,200);
    }

    public function store(TransactionCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = $request->user()->id;
        $transaction = $this->transactionService->create($data,$userId);

        return response()->json([
            'data' => $transaction
        ],201);
    }

    public function update(TransactionUpdateRequest $request, Transaction $transaction)
    {
        $this->authorize("update", $transaction);
        $data = $request->validated();
        $userId = $request->user()->id;
        $transactionId = $transaction->id;

        $transactionUpdated = $this->transactionService->update($transactionId, $data, $userId);

        return response()->json([
            "data" => $transactionUpdated
        ],200);
    }

    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorize("view", $transaction);

        return response()->json([
            "data" => $transaction
        ], 200);
    }

    public function destroy(Transaction $transaction)
    {
       $this->authorize("delete", $transaction);
       $this->transactionService->delete($transaction);

       return response()->json([
            "message" => "Transacao deletada com sucesso"
       ], 200);
    }

    public function getByPeriod(Request $request)
    {
        $transactions = $this->transactionService->getbyPeriod($request->user()->id, $request->startDate, $request->endDate);

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
