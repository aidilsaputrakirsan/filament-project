<?php
// routes/student.php

use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\GradeController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':student'])->name('student.')->group(function () {
    // Dashboard
       Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/lessons/{lesson}', [CourseController::class, 'showLesson'])->name('courses.lesson');
    
    // Assignments
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
    
    // Quizzes
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::get('/quizzes/attempt/{attempt}', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/attempt/{attempt}/save-answer', [QuizController::class, 'saveAnswer'])->name('quizzes.saveAnswer');
    Route::post('/quizzes/attempt/{attempt}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/review/{attempt}', [QuizController::class, 'review'])->name('quizzes.review');
    
    // Attendances
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances');
    Route::get('/attendances/course/{course}', [AttendanceController::class, 'showByCourse'])->name('attendances.course');
    
    // Grades
    Route::get('/grades', [GradeController::class, 'index'])->name('grades');
    Route::get('/grades/course/{course}', [GradeController::class, 'showCourse'])->name('grades.course');
});