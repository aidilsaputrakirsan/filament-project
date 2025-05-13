<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role
                if ($user->isAdmin() || $user->isTeacher()) {
                    return redirect('/admin');
                } else if ($user->isStudent()) {
                    return redirect()->route('student.dashboard');
                }
                
                // Fallback ke dashboard umum jika tidak ada role yang cocok
                return redirect('/');
            }
        }

        return $next($request);
    }
}