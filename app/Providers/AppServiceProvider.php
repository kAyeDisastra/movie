<?php

namespace App\Providers;

use App\Models\Studio;
use App\Observers\StudioObserver;
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
        Studio::observe(StudioObserver::class);
    }
}
