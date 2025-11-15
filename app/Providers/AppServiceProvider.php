<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\BudgetRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TransactionRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
        $this->app->bind(
            AccountRepositoryInterface::class,
             AccountRepository::class
        );

        $this->app->bind(
            BudgetRepositoryInterface::class,
             BudgetRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
