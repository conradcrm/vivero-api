<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
use Illuminate\Support\Facades\Schema; 
>>>>>>> main

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
        Schema::defaultStringLength(191);
=======
        Schema::defaultStringLength(160); 
>>>>>>> main
    }
}
