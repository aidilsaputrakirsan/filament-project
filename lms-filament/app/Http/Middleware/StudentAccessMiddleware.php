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
        // Hanya redirect siswa, izinkan admin dan guru
        if (Auth::check() && Auth::user()->role === 'student') {
            // Gunakan access-denied route daripada redirect ke route yang mungkin belum didefinisikan
            return redirect('/admin/access-denied');
        }

        return $next($request);
    }
}