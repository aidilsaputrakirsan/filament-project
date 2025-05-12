<?php
// app/Http/Middleware/StudentAccessMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('filament.admin.pages.access-denied');
        }

        return $next($request);
    }
}