<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

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
        if (Schema::hasTable('settings')) {
            $siteSettings = DB::table('settings')->pluck('value', 'key')->all();
            View::share('siteSettings', $siteSettings);
        } else {
            View::share('siteSettings', []);
        }
        // Use Bootstrap for pagination styling
        Paginator::useBootstrap();
    }
}
