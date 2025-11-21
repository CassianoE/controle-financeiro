<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Services\AccountService;
use App\DTOs\CreateTransactionDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\UnauthorizedAccountAccessException;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private AccountService $accountService,
        private AccountRepositoryInterface $accountRepository,
    ) {}

    public function getAll($userId, ?int $accountId = null, ?int $categoryId = null)
    {
        return $this->transactionRepository->getAll($userId, $accountId, $categoryId);
    }

    public function findById(int $id)
    {
        return $this->transactionRepository->findById($id);
    }

    public function create(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {

        $transactionDTO = CreateTransactionDTO::fromArray($data);
        $transactionDTO->user_id = $userId;

        $account = $this->accountRepository->findById($transactionDTO->account_id);

        if ($account->user_id !== $userId) {
            throw new UnauthorizedAccountAccessException();
        }

        $newTransaction = $this->transactionRepository->create($transactionDTO->toArray());

        if ($newTransaction->type === 'income') {
             $account->deposit($newTransaction->amount);
        } else {
            $account->withdraw($newTransaction->amount);
        }

        $this->accountRepository->update($account, [
            'balance' => $account->balance
        ]);

        return $newTransaction;
        });
    }

    public function update(int $transactionId, array $data, int $userId)
    {
        return DB::transaction(function () use ($transactionId, $data, $userId){

            $transaction = $this->transactionRepository->findById($transactionId);
            $account = $this->accountRepository->findById($transaction->account_id);

             if ($transaction->type === 'income') {
                 $account->withdraw($transaction->amount); 
             } else {
                 $account->deposit($transaction->amount);
             }

        $transactionUpdated = $this->transactionRepository->update($transaction->id, $data);

             if ($transactionUpdated->type === 'income') {
                $account->deposit($transactionUpdated->amount);
             } else {
                 $account->withdraw($transactionUpdated->amount);
             }

        $this->accountRepository->update($account,[
            'balance' => $account->balance
        ]);

        return $transactionUpdated;

        });
    }

    public function delete(Transaction $transaction)
    {
        return $this->transactionRepository->delete($transaction);
    }

    public function getbyPeriod(int $userId, ?String $startDate, ?String $endDate): Collection
    {
        return $this->transactionRepository->getbyPeriod($userId, $startDate, $endDate);
    }

    public function getSummaryByPeriod(int $userId, ?String $startDate, ?String $endDate):array
    {
        return $this->transactionRepository->getSummaryByPeriod($userId, $startDate, $endDate);
    }
}
