<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category; 
use App\Models\Account;  

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $transactionId = $this->route('transaction');

        if (empty($transactionId)){
           $accountId = $this->input('account_id');

           if (empty($accountId)) {
                return false; 
            }

            $account = Account::find($accountId);

            return $account && $account->user_id === $this->user()->id;
        } else {

            $transaction = Transaction::with('account')->find($transactionId);

            if(!$transaction || !$transaction->account){
                return false;
            }

            return $transaction->account->user_id === $this->user()->id;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'account_id' => 'required|exists:accounts,id',
        ];
    }
}
