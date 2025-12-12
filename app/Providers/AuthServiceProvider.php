<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
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
        // Define a gate named 'isAdmin'
        // $user = currently logged-in user
        Gate::define('isAdmin', function ($user) {
            // Check if the logged-in user's role is admin
            return $user->email === 'admin@example.com';
        });
    }
    
}
