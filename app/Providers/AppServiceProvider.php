<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Restaurant;


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
        // Share $managerRestaurants with the 'layouts.restaurant' view
        View::composer('layouts.restaurant', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('restaurant_manager')) {
                $managerRestaurants = Restaurant::where('user_id', auth()->id())->get();
                $view->with('managerRestaurants', $managerRestaurants);
            }
        });
    }
}
