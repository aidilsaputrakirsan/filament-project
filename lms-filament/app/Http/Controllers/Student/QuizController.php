<?php
// app/Http/Controllers/Student/QuizController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourseIds = $user->enrolledCourses->pluck('id');
        
        $upcomingQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->where('start_time', '>', now())
            ->where('is_published', true)
            ->orderBy('start_time')
            ->get();
            
        $activeQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where('is_published', true)
            ->orderBy('end_time')
            ->get();
            
        $pastQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->where('end_time', '<', now())
            ->where('is_published', true)
            ->orderBy('end_time', 'desc')
            ->get();
        
        return view('student.quizzes.index', compact('upcomingQuizzes', 'activeQuizzes', 'pastQuizzes'));
    }
    
    public function show(Quiz $quiz)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $quiz->course_id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda tidak memiliki akses ke kuis ini.');
        }
        
        // Check if quiz is published
        if (!$quiz->is_published) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Kuis ini belum dipublikasikan.');
        }
        
        // Get user's attempts for this quiz
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.quizzes.show', compact('quiz', 'attempts'));
    }
    
    public function start(Quiz $quiz)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $quiz->course_id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda tidak memiliki akses ke kuis ini.');
        }
        
        // Check if quiz is published
        if (!$quiz->is_published) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Kuis ini belum dipublikasikan.');
        }
        
        // Check if quiz is active
        if ($quiz->start_time > now() || $quiz->end_time < now()) {
            return redirect()->route('student.quizzes.show', $quiz)
                ->with('error', 'Kuis ini tidak sedang aktif.');
        }
        
        // Check if user already has an ongoing attempt
        $ongoingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();
            
        if ($ongoingAttempt) {
            return redirect()->route('student.quizzes.attempt', $ongoingAttempt);
        }
        
        // Create new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'start_time' => now(),
            'status' => 'in_progress',
        ]);
        
        return redirect()->route('student.quizzes.attempt', $attempt);
    }
    
    public function attempt(QuizAttempt $attempt)
    {
        $user = Auth::user();
        
        // Check if this attempt belongs to the user
        if ($attempt->user_id !== $user->id) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda tidak memiliki akses ke upaya ini.');
        }
        
        // Check if attempt is still in progress
        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.quizzes.review', $attempt)
                ->with('error', 'Upaya ini sudah selesai.');
        }
        
        // Check if quiz time is up
        $quiz = $attempt->quiz;
        $timeLeft = $attempt->start_time->addMinutes($quiz->duration_minutes)->diffInSeconds(now(), false);
        
        if ($timeLeft <= 0) {
            // Auto-submit the quiz
            $this->submitQuiz($attempt);
            return redirect()->route('student.quizzes.review', $attempt)
                ->with('error', 'Waktu pengerjaan kuis telah habis.');
        }
        
        // Get all questions with options
        $questions = $quiz->questions()->with('options')->get();
        
        // Get user's answers
        $answers = $attempt->answers()->pluck('question_option_id', 'question_id')
            ->toArray();
        
        return view('student.quizzes.attempt', compact('attempt', 'quiz', 'questions', 'answers', 'timeLeft'));
    }
    
    public function saveAnswer(Request $request, QuizAttempt $attempt)
    {
        $user = Auth::user();
        
        // Check if this attempt belongs to the user
        if ($attempt->user_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak'], 403);
        }
        
        // Check if attempt is still in progress
        if ($attempt->status !== 'in_progress') {
            return response()->json(['status' => 'error', 'message' => 'Upaya sudah selesai'], 400);
        }
        
        // Validate request
        $validated = $request->validate([
            'question_id' => 'required|integer|exists:questions,id',
            'answer' => 'required',
        ]);
        
        $question = \App\Models\Question::find($validated['question_id']);
        
        // Check if question belongs to this quiz
        if ($question->quiz_id !== $attempt->quiz_id) {
            return response()->json(['status' => 'error', 'message' => 'Pertanyaan tidak valid'], 400);
        }
        
        DB::beginTransaction();
        try {
            // Delete previous answer if exists
            QuestionAnswer::where('quiz_attempt_id', $attempt->id)
                ->where('question_id', $question->id)
                ->delete();
            
            // Create new answer
            if ($question->question_type === 'essay') {
                QuestionAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'answer_text' => $validated['answer'],
                ]);
            } else {
                QuestionAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'question_option_id' => $validated['answer'],
                ]);
            }
            
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan jawaban'], 500);
        }
    }
    
    public function submit(Request $request, QuizAttempt $attempt)
    {
        $user = Auth::user();
        
        // Check if this attempt belongs to the user
        if ($attempt->user_id !== $user->id) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda tidak memiliki akses ke upaya ini.');
        }
        
        // Check if attempt is still in progress
        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.quizzes.review', $attempt)
                ->with('error', 'Upaya ini sudah selesai.');
        }
        
        $this->submitQuiz($attempt);
        
        return redirect()->route('student.quizzes.review', $attempt)
            ->with('success', 'Kuis berhasil diselesaikan.');
    }
    
    private function submitQuiz(QuizAttempt $attempt)
    {
        $quiz = $attempt->quiz;
        $questions = $quiz->questions;
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;
        
        // Calculate score for multiple choice and true/false questions
        foreach ($questions as $question) {
            if ($question->question_type !== 'essay') {
                $answer = QuestionAnswer::where('quiz_attempt_id', $attempt->id)
                    ->where('question_id', $question->id)
                    ->first();
                
                if ($answer && $answer->questionOption && $answer->questionOption->is_correct) {
                    $earnedPoints += $question->points;
                    $answer->score = $question->points;
                    $answer->save();
                }
            }
        }
        
        // Calculate final score as percentage
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        
        // Update attempt
        $attempt->end_time = now();
        $attempt->score = $score;
        $attempt->status = 'completed';
        $attempt->save();
        
        return $attempt;
    }
    
    public function review(QuizAttempt $attempt)
    {
        $user = Auth::user();
        
        // Check if this attempt belongs to the user
        if ($attempt->user_id !== $user->id) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda tidak memiliki akses ke upaya ini.');
        }
        
        // If attempt is still in progress, redirect to attempt page
        if ($attempt->status === 'in_progress') {
            return redirect()->route('student.quizzes.attempt', $attempt);
        }
        
        $quiz = $attempt->quiz;
        $questions = $quiz->questions()->with('options')->get();
        
        // Get user's answers with details
        $answers = $attempt->answers()
            ->with(['question', 'questionOption'])
            ->get()
            ->keyBy('question_id');
        
        return view('student.quizzes.review', compact('attempt', 'quiz', 'questions', 'answers'));
    }
}