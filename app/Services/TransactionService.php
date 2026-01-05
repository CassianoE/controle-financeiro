<?php

namespace App\Services;

use App\Models\Transaction;
use App\DTOs\CreateTransactionDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\UnauthorizedAccountAccessException;
use App\Exceptions\UnauthorizedCategoryAccessException;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private AccountRepositoryInterface $accountRepository,
        private CategoryRepositoryInterface $categoryRepository,
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

        $category = $this->categoryRepository->findById($transactionDTO->category_id);

        if ($category->user_id !== $userId) {
            throw new UnauthorizedCategoryAccessException();
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
            $oldAccount = $this->accountRepository->findById($transaction->account_id);

            if (isset($data['account_id'])) {
                $newAccount = $this->accountRepository->findById($data['account_id']);

                if ($newAccount->user_id !== $userId) {
                    throw new UnauthorizedAccountAccessException();
                }
            }

            if (isset($data['category_id'])) {
                $category = $this->categoryRepository->findById($data['category_id']);

                if ($category->user_id !== $userId) {
                    throw new UnauthorizedCategoryAccessException();
                }
            }

            // Reverte o saldo da conta antiga
            if ($transaction->type === 'income') {
                $oldAccount->withdraw($transaction->amount); 
            } else {
                $oldAccount->deposit($transaction->amount);
            }

            $this->accountRepository->update($oldAccount, [
                'balance' => $oldAccount->balance
            ]);

            // Atualiza a transação
            $transactionUpdated = $this->transactionRepository->update($transaction->id, $data);

            // Determina qual conta usar (nova se mudou, senão a antiga)
            $accountToUpdate = isset($data['account_id']) && $data['account_id'] !== $transaction->account_id
                ? $this->accountRepository->findById($data['account_id'])
                : $oldAccount;

            // Aplica o novo saldo na conta correta
            if ($transactionUpdated->type === 'income') {
                $accountToUpdate->deposit($transactionUpdated->amount);
            } else {
                $accountToUpdate->withdraw($transactionUpdated->amount);
            }

            $this->accountRepository->update($accountToUpdate, [
                'balance' => $accountToUpdate->balance
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
