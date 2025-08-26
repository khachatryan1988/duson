<?php
namespace Webs\Transaction\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Webs\Transaction\Nova\Payment;
use Webs\Transaction\Nova\Transaction;

class TransactionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/getaways.php', 'getaway');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishFiles();
    }

    private function publishFiles(){
        $this->publishes([__DIR__ . '/../../config/getaways.php' => config_path('getaways.php')], 'getaways_config');
    }

    public function register()
    {
    }
}
