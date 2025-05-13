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
    ];
}