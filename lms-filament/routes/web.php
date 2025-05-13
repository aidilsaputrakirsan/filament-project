<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
});

// Arahkan setelah login ke halaman yang sesuai berdasarkan role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (in_array(auth()->user()->role, ['admin', 'teacher'])) {
            return redirect('/admin');
        } else {
            return redirect()->route('student.dashboard');
        }
    }
    
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/student.php';