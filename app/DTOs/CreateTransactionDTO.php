<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Auth;

class CreateTransactionDTO
{
    public function __construct(
        public string $title,
        public float $amount,
        public string $date,
        public string $type,
        public int $category_id,
         public int $account_id,
        public ?string $description = null,
        public ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            amount: (float) $data['amount'],
            date: $data['date'],
            type: $data['type'],
            category_id: (int) $data['category_id'],
            account_id: (int) $data['account_id'],
            description: $data['description'] ?? null,
            user_id: Auth::id()
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'amount' => $this->amount,
            'date' => $this->date,  // Garantindo que o campo date está sendo incluído
            'type' => $this->type,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'account_id' => $this->account_id
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}