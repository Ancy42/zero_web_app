<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if(request()->ip() != '127.0.0.1'){
            Schema::defaultStringLength(191);
            if (!file_exists(base_path('storage/installed')) && !request()->is('install') && !request()->is('install/*')) {
                header("Location: install");
                exit;
            }
        }
    }
}
