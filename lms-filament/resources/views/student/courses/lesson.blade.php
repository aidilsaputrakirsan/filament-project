<!-- resources/views/student/courses/lesson.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $lesson->title }}</x-slot>
    <x-slot name="header">{{ $lesson->title }}</x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <div class="mb-4">
                <a href="{{ route('student.courses.show', $course) }}" class="text-amber-600 hover:underline flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke {{ $course->title }}
                </a>
            </div>
            
            <div class="mt-6">
                <h3 class="text-xl font-medium text-gray-900 mb-4">{{ $lesson->title }}</h3>
                
                <div class="prose max-w-none text-gray-600">
                    {!! $lesson->content ?: 'Tidak ada konten yang tersedia.' !!}
                </div>
            </div>
        </div>
    </div>
</x-student-layout>