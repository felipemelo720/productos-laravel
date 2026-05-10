<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        Product::observe(\App\Observers\ProductObserver::class);
    }
}
