<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'description' => $this->faker->paragraph,
        ];
    }
}
