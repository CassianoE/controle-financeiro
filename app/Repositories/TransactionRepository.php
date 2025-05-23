<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAll(): Collection
    {
        return Transaction::all();
    }

    public function findById(int $id)
    {
        return Transaction::findOrFail($id);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update(int $id, array $data)
    {
        $transaction = $this->findById($id);
        $transaction->update($data);
        return $transaction;
    }

    public function delete(int $id)
    {
        $transaction = $this->findById($id);
        $transaction->delete();
    }

    public function getBalance(int $userId): float
    {
        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        return $income - $expense;
    }
}