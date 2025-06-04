<?php

namespace App\Providers;

use App\Models\Karyawan;
use App\Models\User;
use App\Observers\KaryawanObserver;
use App\Observers\UserObserver;
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
        // Register observers
        Karyawan::observe(KaryawanObserver::class);
        User::observe(UserObserver::class);
    }
}
