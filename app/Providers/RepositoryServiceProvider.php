<?php

namespace App\Providers;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\FamilyRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\FamilyRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            FamilyRepositoryInterface::class,
            FamilyRepository::class,
        );

        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class,
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
