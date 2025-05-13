<!-- resources/views/student/dashboard.blade.php -->
<x-student-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">Dashboard Mahasiswa</x-slot>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Enrolled Courses -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Mata Kuliah Saya</h3>
                
                @if($enrolledCourses->count() > 0)
                    <div class="space-y-4">
                        @foreach($enrolledCourses as $course)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                <a href="{{ route('student.courses.show', $course) }}" class="block">
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $course->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Pengajar: {{ $course->user->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('student.courses.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Lihat semua mata kuliah &rarr;</a>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Anda belum terdaftar pada mata kuliah apapun.</p>
                @endif
            </div>
        </div>
        
        <!-- Upcoming Assignments -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tugas yang Akan Datang</h3>
                
                @if($upcomingAssignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingAssignments as $assignment)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                <a href="{{ route('student.assignments.show', $assignment) }}" class="block">
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $assignment->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $assignment->course->title }}</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Batas waktu: {{ $assignment->due_date->format('d M Y, H:i') }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('student.assignments.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Lihat semua tugas &rarr;</a>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada tugas yang akan datang.</p>
                @endif
            </div>
        </div>
        
        <!-- Upcoming Quizzes -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Kuis yang Akan Datang</h3>
                
                @if($upcomingQuizzes->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingQuizzes as $quiz)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                <a href="{{ route('student.quizzes.show', $quiz) }}" class="block">
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $quiz->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->course->title }}</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        Waktu: {{ $quiz->start_time->format('d M Y, H:i') }} - {{ $quiz->end_time->format('d M Y, H:i') }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('student.quizzes.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Lihat semua kuis &rarr;</a>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada kuis yang akan datang.</p>
                @endif
            </div>
        </div>
        
        <!-- Recent Submissions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengumpulan Tugas Terbaru</h3>
                
                @if($recentSubmissions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentSubmissions as $submission)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                <a href="{{ route('student.assignments.show', $submission->assignment) }}" class="block">
                                    <h4 class="font-medium text-amber-600 dark:text-amber-400">{{ $submission->assignment->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $submission->assignment->course->title }}</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        Dikumpulkan: {{ $submission->created_at->format('d M Y, H:i') }}
                                    </p>
                                    @if($submission->score !== null)
                                        <p class="text-sm text-green-600 dark:text-green-400">
                                            Nilai: {{ $submission->score }}/{{ $submission->assignment->max_score }}
                                        </p>
                                    @else
                                        <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                            Belum dinilai
                                        </p>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Anda belum mengumpulkan tugas apapun.</p>
                @endif
            </div>
        </div>
    </div>
</x-student-layout>