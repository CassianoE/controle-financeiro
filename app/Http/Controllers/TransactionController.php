<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function index()
    {
        return response()->json($this->transactionService->getAll());
    }

    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->create($request->validated());
        
        return response()->json([
            'message' => 'Transação criada com sucesso',
            'data' => $transaction
            ], 201);
    }

    public function show(int $id)
    {
        try {
            $transaction = $this->transactionService->findById($id);
    
            return response()->json([
                'message' => 'Transação encontrada',
                'data' => $transaction
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transação não encontrada',
            ], 404);
        }
    }

    public function update(TransactionRequest $request, int $id)
    {
        $transaction = $this->transactionService->update($id, $request->validated());
        
        return response()->json([
            'message' => 'Transação atualizada com sucesso',
            'data' => $transaction
            ], 200);
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

    public function getBalance()
    {
        $balance = $this->transactionService->getBalance(Auth::id());

        return response()->json([
            'message' => 'Saldo calculado com sucesso',
            'Saldo' => $balance
        ], 200);
    }
    

}
