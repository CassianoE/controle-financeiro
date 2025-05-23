<?php

namespace App\Services;

use App\DTOs\CreateTransactionDTO;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function getAll()
    {
        return $this->transactionRepository->getAll();
    }

    public function findById(int $id)
    {
        return $this->transactionRepository->findById($id);
    }

    public function create(array $data)
    {
        
        $transactionDTO = CreateTransactionDTO::fromArray($data);

        return $this->transactionRepository->create($transactionDTO->toArray());
    }

    public function update(int $id, array $data)
    {
        return $this->transactionRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->transactionRepository->delete($id);
    }

    public function getBalance(int $userId): float
    {
        return $this->transactionRepository->getBalance($userId);
    }
}
