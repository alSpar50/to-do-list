<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceRootUrl(config('app.url'));

        // Jeśli korzystasz z HTTPS w produkcji
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }

    // ...
}

