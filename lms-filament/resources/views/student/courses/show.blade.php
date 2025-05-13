<!-- resources/views/student/courses/show.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $course->title }}</x-slot>
    <x-slot name="header">{{ $course->title }}</x-slot>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Mata Kuliah</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Pengajar: {{ $course->user->name }}</p>
            </div>
            
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Deskripsi</h4>
                <div class="text-gray-600 dark:text-gray-400 prose dark:prose-invert max-w-none">
                    {{ $course->description ?: 'Tidak ada deskripsi.' }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Materials -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Materi Pembelajaran</h3>
            
            @if($lessons->count() > 0)
                <div class="space-y-4">
                    @foreach($lessons as $lesson)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                            <a href="{{ route('student.courses.lesson', [$course, $lesson]) }}" class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $lesson->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ Str::limit(strip_tags($lesson->content), 100) ?: 'Klik untuk melihat materi.' }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">Belum ada materi yang tersedia.</p>
            @endif
        </div>
    </div>
    
    <!-- Assignments -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tugas</h3>
            
            @if($assignments->count() > 0)
                <div class="space-y-4">
                    @foreach($assignments as $assignment)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $assignment->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Batas waktu: {{ $assignment->due_date->format('d M Y, H:i') }}
                                    </p>
                                    
                                    @php
                                        $submission = $assignment->submissions()->where('user_id', Auth::id())->first();
                                    @endphp
                                    
                                    @if($submission)
                                        @if($submission->score !== null)
                                            <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                                Dinilai: {{ $submission->score }}/{{ $assignment->max_score }}
                                            </p>
                                        @else
                                            <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                                Dikumpulkan, menunggu penilaian
                                            </p>
                                        @endif
                                    @else
                                        @if($assignment->due_date < now())
                                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                                Batas waktu telah lewat
                                            </p>
                                        @else
                                            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                                Belum dikumpulkan
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">Belum ada tugas yang tersedia.</p>
            @endif
        </div>
    </div>
    
    <!-- Quizzes -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Kuis & Test</h3>
            
            @if($quizzes->count() > 0)
                <div class="space-y-4">
                    @foreach($quizzes as $quiz)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                            <a href="{{ route('student.quizzes.show', $quiz) }}" class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $quiz->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Jenis: 
                                        @if($quiz->quiz_type === 'pre_test')
                                            Pre-Test
                                        @elseif($quiz->quiz_type === 'post_test')
                                            Post-Test
                                        @else
                                            Kuis
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Waktu: {{ $quiz->start_time->format('d M Y, H:i') }} - {{ $quiz->end_time->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Durasi: {{ $quiz->duration_minutes }} menit
                                    </p>
                                    
                                    @php
                                        $attempt = $quiz->attempts()->where('user_id', Auth::id())->where('status', 'completed')->orderBy('score', 'desc')->first();
                                    @endphp
                                    
                                    @if($attempt)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            Nilai: {{ number_format($attempt->score, 2) }}
                                        </p>
                                    @else
                                        @if($quiz->start_time > now())
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                Belum dimulai
                                            </p>
                                        @elseif($quiz->end_time < now())
                                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                                Tidak diikuti
                                            </p>
                                        @else
                                            <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                                Tersedia untuk dikerjakan
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">Belum ada kuis yang tersedia.</p>
            @endif
        </div>
    </div>
</x-student-layout>