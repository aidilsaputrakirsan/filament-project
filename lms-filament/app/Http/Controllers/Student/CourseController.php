<?php
// app/Http/Controllers/Student/CourseController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()->with('user')->get();
        
        return view('student.courses.index', compact('enrolledCourses'));
    }
    
    public function show(Course $course)
    {
        // Check if student is enrolled
        $user = Auth::user();
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.courses.index')
                ->with('error', 'Anda tidak terdaftar pada mata kuliah ini.');
        }
        
        $lessons = $course->lessons()->orderBy('order')->get();
        $assignments = $course->assignments()->orderBy('due_date')->get();
        $quizzes = $course->quizzes()->where('is_published', true)->orderBy('start_time')->get();
        
        return view('student.courses.show', compact('course', 'lessons', 'assignments', 'quizzes'));
    }
    
    public function showLesson(Course $course, Lesson $lesson)
    {
        // Check if student is enrolled
        $user = Auth::user();
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        
        if (!$isEnrolled || $lesson->course_id !== $course->id) {
            return redirect()->route('student.courses.index')
                ->with('error', 'Anda tidak memiliki akses ke materi ini.');
        }
        
        return view('student.courses.lesson', compact('course', 'lesson'));
    }
}