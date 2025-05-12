<?php
// app/Providers/RouteServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // ...

    public function boot(): void
    {
        // ...
        
        $this->routes(function () {
            // ...

            // Tambahkan middleware untuk akses student ke panel admin
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            
            // Tambahkan route khusus untuk student
            Route::middleware(['web', 'auth'])
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));
        });
    }
}