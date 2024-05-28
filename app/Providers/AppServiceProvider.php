<?php

namespace App\Providers;

use App\Services\PineconeSearch;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\Facades\Actions;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Actions::registerCommands();
        $this->app->singleton(PineconeSearch::class, function ($app) {
            return new PineconeSearch();
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
