<?php
// routes/student.php

use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\QuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('courses', CourseController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::resource('quizzes', QuizController::class);
});