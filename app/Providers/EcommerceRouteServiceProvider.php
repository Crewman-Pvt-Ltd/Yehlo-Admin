<?php

// namespace App\Providers;

// use Illuminate\Support\ServiceProvider;

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class EcommerceRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->routes(function () {
            Route::prefix('ecommerce')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/ecommerce.php'));
        });
    }
}
