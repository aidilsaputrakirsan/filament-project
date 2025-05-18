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
         // Hanya redirect mahasiswa, izinkan admin dan dosen
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            // Gunakan access-denied route
            return redirect('/admin/access-denied');
        }

        return $next($request);
    }
}