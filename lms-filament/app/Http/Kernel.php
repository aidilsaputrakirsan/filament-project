<?php
// app/Http/Kernel.php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...

    protected $middlewareAliases = [
        'student.access' => \App\Http\Middleware\StudentAccessMiddleware::class,  
        'role' => \App\Http\Middleware\CheckRole::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}