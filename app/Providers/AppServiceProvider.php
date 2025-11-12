<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

        $this->app->singleton(\App\Repositories\UserDao::class, function($app) {
        return new \App\Repositories\UserDao(new \App\Models\User);
    });

    $this->app->singleton(\App\Business\UserBo::class, function($app) {
        return new \App\Business\UserBo();
    });

    $this->app->singleton(\App\Services\UserService::class, function($app) {
        return new \App\Services\UserService($app->make(\App\Repositories\UserDao::class), $app->make(\App\Business\UserBo::class));
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
