<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\TransactionRepositoryInterface;

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


    public function queryByPeriod(int $userId, ?String $startDate, ?String $endDate): Collection
    {

        $query = Transaction::where('user_id', $userId);

        if($startDate){ 
            $query->where('date', '>=', $startDate);
        }

        if($endDate){
            $query->where('date', '<=', $endDate);
        }

        return $query->get();
    }

    public function getbyPeriod(int $userId, ?String $startDate, ?String $endDate): Collection
    {
        return $this->queryByPeriod($userId, $startDate, $endDate);
    }

    public function getSummaryByPeriod(int $userId, ?String $startDate, ?String $endDate):array
    {
        $query = $this->queryByPeriod($userId, $startDate, $endDate);

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense
        ];
    }

    
    
}