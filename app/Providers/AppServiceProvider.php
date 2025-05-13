<?php

namespace App\Providers;

use App\Models\Websitesetup;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/extra_functions.php');

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('website_settings', WebsiteSetup::first());
            $view->with('date',Carbon::today());
        });    }
}
