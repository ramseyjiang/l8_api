<?php

namespace App\Providers;

use App\Contracts\EloquentContract;
use App\Contracts\UserContract;

use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;

use Illuminate\Support\ServiceProvider;

class ContractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentContract::class, BaseRepository::class);
        $this->app->bind(UserContract::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
