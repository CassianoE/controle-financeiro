<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Models\Account;

class AccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["sometimes", "string", "max:255"],
            "type" => ["sometimes", new Enum(AccountType::class)],
            "balance" => ["sometimes", "numeric"],
            "status" => ["sometimes", new Enum(AccountStatus::class)],
        ];
    }
}
