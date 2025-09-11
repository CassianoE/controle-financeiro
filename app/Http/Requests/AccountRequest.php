<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;
use Illuminate\Validation\Rules\Enum;
use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Models\Account;

class AccountRequest extends FormRequest
{

    public function authorize(): bool
    {
        $account_id_from_url = $this->route('account');

        if (empty($account_id_from_url)){
            return true;
        }

        $account = Account::find($account_id_from_url);

        return $account && $account->user_id === $this->user()->id;
    }


    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:savings,checking,credit,cash,investment,other',
            'balance' => 'required|numeric',
            'status' => ['required', new Enum(AccountStatus::class)],
        ];
    }
}
