<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Karyawan;
use App\Models\User;
use App\Observers\KaryawanObserver;
use App\Observers\UserObserver;

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
