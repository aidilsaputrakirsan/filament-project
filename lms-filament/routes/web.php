<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Definisi explicit untuk route dashboard
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isTeacher()) {
            return redirect('/admin');
        } else if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
    }
    
    return redirect('/');
})->middleware(['auth'])->name('dashboard'); // Pastikan ada nama 'dashboard' di sini

require __DIR__.'/auth.php';
require __DIR__.'/student.php';