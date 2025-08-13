<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\AccountStatus;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'type' => 'savings',
            'balance' => $this->faker->numberBetween(100, 10000),
            'status' => AccountStatus::ACTIVE,
        ];
    }
}
