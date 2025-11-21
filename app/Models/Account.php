<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Enums\AccountStatus;
use App\Exceptions\NegativeBalanceNotAllowedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'balance',
        'status',
    ];

    protected $casts = [
        'status' => AccountStatus::class,
        'type' => AccountType::class
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposit(float $amount)
    {
        $this->balance += $amount;
    }

    public function withdraw(float $amount)
    {
        $newBalance = $this->balance - $amount;

        if($this->type !== AccountType::CREDIT && $newBalance < 0){
            throw new NegativeBalanceNotAllowedException();
        }

        $this->balance = $newBalance;
    }
}

