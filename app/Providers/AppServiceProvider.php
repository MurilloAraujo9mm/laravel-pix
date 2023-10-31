<?php

namespace App\Providers;

use App\Models\Account;
use App\Observers\AccountObserver;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\AMQP\AMQPInterface;
use App\Services\AMQP\AMQService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AMQPInterface::class, AMQService::class);
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Account::observe(AccountObserver::class);
    }
}
