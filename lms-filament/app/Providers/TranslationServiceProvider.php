<?php

// app/Providers/TranslationServiceProvider.php
namespace App\Providers;

use App\Services\LanguageService;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('language.service', function ($app) {
            return new LanguageService();
        });
    }

    public function boot(): void
    {
        //
    }
}