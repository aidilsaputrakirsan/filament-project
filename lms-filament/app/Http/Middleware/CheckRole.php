<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user() && $request->user()->role === 'student') {
                return redirect()->route('student.dashboard');
            }
            
            if ($request->user() && in_array($request->user()->role, ['admin', 'teacher'])) {
                return redirect('/admin');
            }
            
            return redirect('/login');
        }

        return $next($request);
    }
}