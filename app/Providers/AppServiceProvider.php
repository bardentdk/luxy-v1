<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Rien ici — GroqService lit config() directement dans ses méthodes
    }

    public function boot(): void
    {
        //
    }
}