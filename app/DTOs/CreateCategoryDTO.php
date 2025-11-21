<?php

namespace App\DTOs;

use App\Enums\CategoryType;
use Illuminate\Support\Facades\Auth;


class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public CategoryType $type,
        public int $account_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: CategoryType::from($data['type']),
            account_id: $data['account_id']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value,
            'account_id' => $this->account_id
        ];
    }
}
