<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Auth;


class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public string $type,
        public int $user_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: $data['type'],
            user_id: Auth::id()
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'user_id' => $this->user_id
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
