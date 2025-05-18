<?php

// app/Http/Middleware/SetLocaleFromUserPreference.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUserPreference
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            app()->setLocale(auth()->user()->language_preference ?? 'id');
        } else {
            app()->setLocale($request->session()->get('locale', 'id'));
        }
        
        return $next($request);
    }
}
