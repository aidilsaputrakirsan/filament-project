<!-- resources/views/student/courses/index.blade.php -->
<x-student-layout>
    <x-slot name="title">Mata Kuliah</x-slot>
    <x-slot name="header">Mata Kuliah Saya</x-slot>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            @if($enrolledCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledCourses as $course)
                        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Pengajar: {{ $course->user->name }}</p>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                                        {{ $course->description ?: 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Lihat Mata Kuliah
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Anda belum terdaftar pada mata kuliah apapun.</p>
                </div>
            @endif
        </div>
    </div>
</x-student-layout>