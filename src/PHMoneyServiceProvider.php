<?php

namespace Kainotomo\PHMoney;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PHMoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/phmoney.php', 'phmoney');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'phmoney');

        Route::group([
            'domain' => config('jetstream.domain', null),
            'prefix' => config('jetstream.prefix', config('jetstream.path')),
            ['middleware' => config('jetstream.middleware', ['web'])]
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }
}
