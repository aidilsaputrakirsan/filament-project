<?php
// app/Http/Controllers/Student/AssignmentController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourseIds = $user->enrolledCourses->pluck('id');
        
        $assignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'submissions' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('due_date')
            ->get();
        
        return view('student.assignments.index', compact('assignments'));
    }
    
    public function show(Assignment $assignment)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $assignment->course_id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();
        
        return view('student.assignments.show', compact('assignment', 'submission'));
    }
    
    public function submit(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $assignment->course_id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }
        
        // Validate request
        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        // Check if submission already exists
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }
        
        if ($submission) {
            // Update existing submission
            $submission->content = $request->content;
            if ($filePath) {
                // Delete old file if exists
                if ($submission->file_path) {
                    Storage::disk('public')->delete($submission->file_path);
                }
                $submission->file_path = $filePath;
            }
            $submission->save();
            
            $message = 'Tugas berhasil diperbarui.';
        } else {
            // Create new submission
            Submission::create([
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
                'content' => $request->content,
                'file_path' => $filePath,
            ]);
            
            $message = 'Tugas berhasil dikumpulkan.';
        }
        
        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', $message);
    }
}