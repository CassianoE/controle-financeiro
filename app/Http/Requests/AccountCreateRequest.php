<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AccountStatus;
use App\Enums\AccountType;

class AccountCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', new Enum(AccountType::class)],
            'balance' => ['required', 'numeric'],
            'status' => ['required', new Enum(AccountStatus::class)],
        ];
    }
}
