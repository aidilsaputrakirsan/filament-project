<?php
// app/Http/Controllers/Student/GradeController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Submission;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses;
        
        $courseGrades = [];
        
        foreach ($enrolledCourses as $course) {
            // Get assignment grades
            $assignments = $course->assignments;
            $assignmentGrades = [];
            
            foreach ($assignments as $assignment) {
                $submission = Submission::where('assignment_id', $assignment->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                $assignmentGrades[] = [
                    'assignment' => $assignment,
                    'submission' => $submission,
                    'score' => $submission ? $submission->score : null,
                    'max_score' => $assignment->max_score,
                ];
            }
            
            // Get quiz grades
            $quizzes = $course->quizzes()->where('is_published', true)->get();
            $quizGrades = [];
            
            foreach ($quizzes as $quiz) {
                $bestAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->orderBy('score', 'desc')
                    ->first();
                
                $quizGrades[] = [
                    'quiz' => $quiz,
                    'attempt' => $bestAttempt,
                    'score' => $bestAttempt ? $bestAttempt->score : null,
                ];
            }
            
            $courseGrades[] = [
                'course' => $course,
                'assignments' => $assignmentGrades,
                'quizzes' => $quizGrades,
            ];
        }
        
        return view('student.grades.index', compact('courseGrades'));
    }
    
    public function showCourse(Course $course)
    {
        $user = Auth::user();
        
        // Check if student is enrolled
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.grades')
                ->with('error', 'Anda tidak terdaftar pada mata kuliah ini.');
        }
        
        // Get assignment grades
        $assignments = $course->assignments;
        $assignmentGrades = [];
        
        foreach ($assignments as $assignment) {
            $submission = Submission::where('assignment_id', $assignment->id)
                ->where('user_id', $user->id)
                ->first();
            
            $assignmentGrades[] = [
                'assignment' => $assignment,
                'submission' => $submission,
                'score' => $submission ? $submission->score : null,
                'max_score' => $assignment->max_score,
                'percentage' => $submission && $submission->score !== null ? ($submission->score / $assignment->max_score) * 100 : null,
            ];
        }
        
        // Get quiz grades
        $quizzes = $course->quizzes()->where('is_published', true)->get();
        $quizGrades = [];
        
        foreach ($quizzes as $quiz) {
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->orderBy('score', 'desc')
                ->get();
            
            $bestAttempt = $attempts->first();
            
            $quizGrades[] = [
                'quiz' => $quiz,
                'attempts' => $attempts,
                'best_attempt' => $bestAttempt,
                'score' => $bestAttempt ? $bestAttempt->score : null,
            ];
        }
        
        return view('student.grades.course', compact('course', 'assignmentGrades', 'quizGrades'));
    }
}