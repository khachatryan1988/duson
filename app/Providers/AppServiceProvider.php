<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OneCClient;
use App\Services\ProductSyncFromOneC;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OneCClient::class, fn() => new OneCClient());
        $this->app->singleton(ProductSyncFromOneC::class, fn($app) => new ProductSyncFromOneC(
            $app->make(OneCClient::class)
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
