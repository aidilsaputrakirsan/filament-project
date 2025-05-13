<?php
// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Courses enrolled
        $enrolledCourses = $user->enrolledCourses()->with('user')->get();
        
        // Upcoming assignments
        $upcomingAssignments = Assignment::whereIn('course_id', $user->enrolledCourses->pluck('id'))
            ->where('due_date', '>', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        // Upcoming quizzes
        $upcomingQuizzes = Quiz::whereIn('course_id', $user->enrolledCourses->pluck('id'))
            ->where('start_time', '>', now())
            ->where('is_published', true)
            ->orderBy('start_time')
            ->take(5)
            ->get();
        
        // Recent submissions
        $recentSubmissions = Submission::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('student.dashboard', compact(
            'enrolledCourses',
            'upcomingAssignments',
            'upcomingQuizzes',
            'recentSubmissions'
        ));
    }
}