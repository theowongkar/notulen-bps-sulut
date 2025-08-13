<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Gate untuk kelola pengguna
        Gate::define('manage-user', function ($user) {
            return $user->role === 'Admin';
        });

        // Gate untuk ubah notulen
        Gate::define('update-minute', function ($user, $minute) {
            return $user->role === 'Admin' || $user->id === $minute->followed_up_by;
        });

        // Gate untuk hapus notulen
        Gate::define('delete-minute', function ($user, $minute) {
            return $user->role === 'Admin' || $user->id === $minute->followed_up_by;
        });
    }
}
