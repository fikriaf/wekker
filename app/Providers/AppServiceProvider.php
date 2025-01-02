<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::addNamespace('layouts_begin_wekker', resource_path('views/layouts/wekker_begin'));
        View::addNamespace('layouts_dashboard_wekker', resource_path('views/layouts/wekker_dashboard'));
    }
}
