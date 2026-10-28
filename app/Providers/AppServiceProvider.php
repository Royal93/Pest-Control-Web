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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Live site and ngrok are https. Locally (php artisan serve on http) forcing
        // https breaks every form and link, so only force it outside local.
        if (! $this->app->environment('local')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
