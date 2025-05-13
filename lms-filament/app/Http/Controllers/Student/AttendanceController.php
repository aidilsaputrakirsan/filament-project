<?php
// app/Http/Controllers/Student/AttendanceController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses;
        
        $attendances = Attendance::where('user_id', $user->id)
            ->with('course')
            ->orderBy('session_date', 'desc')
            ->paginate(15);
        
        $attendanceByCourse = [];
        
        foreach ($enrolledCourses as $course) {
            $total = Attendance::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->count();
                
            $present = Attendance::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->where('status', 'hadir')
                ->count();
                
            $percentage = $total > 0 ? ($present / $total) * 100 : 0;
            
            $attendanceByCourse[$course->id] = [
                'course' => $course,
                'total' => $total,
                'present' => $present,
                'percentage' => $percentage,
            ];
        }
        
        return view('student.attendances.index', compact('attendances', 'attendanceByCourse'));
    }
    
    public function showByCourse(Course $course)
    {
        $user = Auth::user();
        
        // Check if student is enrolled
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.attendances')
                ->with('error', 'Anda tidak terdaftar pada mata kuliah ini.');
        }
        
        $attendances = Attendance::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->orderBy('session_date', 'desc')
            ->get();
        
        $total = $attendances->count();
        $present = $attendances->where('status', 'hadir')->count();
        $percentage = $total > 0 ? ($present / $total) * 100 : 0;
        
        return view('student.attendances.course', compact('course', 'attendances', 'total', 'present', 'percentage'));
    }
}