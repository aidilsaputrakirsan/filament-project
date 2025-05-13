<!-- resources/views/student/quizzes/show.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $quiz->title }}</x-slot>
    <x-slot name="header">{{ $quiz->title }}</x-slot>
    
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <!-- Quiz Details -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="mb-4">
                    <a href="{{ route('student.quizzes.index') }}" class="text-amber-600 hover:underline flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke daftar kuis
                    </a>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Kuis</h3>
                    <p class="text-sm text-gray-600 mt-2">Mata Kuliah: {{ $quiz->course->title }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        Jenis: 
                        @if($quiz->quiz_type === 'pre_test')
                            Pre-Test
                        @elseif($quiz->quiz_type === 'post_test')
                            Post-Test
                        @else
                            Kuis
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mt-1">Waktu Mulai: {{ $quiz->start_time->format('d M Y, H:i') }}</p>
                    <p class="text-sm text-gray-600 mt-1">Waktu Selesai: {{ $quiz->end_time->format('d M Y, H:i') }}</p>
                    <p class="text-sm text-gray-600 mt-1">Durasi: {{ $quiz->duration_minutes }} menit</p>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Deskripsi</h4>
                    <div class="text-gray-600 prose max-w-none">
                        {{ $quiz->description ?: 'Tidak ada deskripsi.' }}
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Status</h4>
                    
                    @if($quiz->start_time > now())
                        <div class="p-3 bg-yellow-100 rounded-md">
                            <p class="text-yellow-800">
                                Kuis ini belum dimulai. Anda dapat mengerjakan kuis ini mulai {{ $quiz->start_time->format('d M Y, H:i') }}.
                            </p>
                        </div>
                    @elseif($quiz->end_time < now())
                        <div class="p-3 bg-red-100 rounded-md">
                            <p class="text-red-800">
                                Kuis ini telah berakhir pada {{ $quiz->end_time->format('d M Y, H:i') }}.
                            </p>
                        </div>
                    @else
                        <div class="p-3 bg-green-100 rounded-md">
                            <p class="text-green-800">
                                Kuis ini sedang berlangsung dan akan berakhir pada {{ $quiz->end_time->format('d M Y, H:i') }}.
                            </p>
                        </div>
                        
                        @php
                            $ongoingAttempt = $attempts->where('status', 'in_progress')->first();
                        @endphp
                        
                        @if($ongoingAttempt)
                            <div class="mt-4">
                                <a href="{{ route('student.quizzes.attempt', $ongoingAttempt) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Lanjutkan Mengerjakan Kuis
                                </a>
                            </div>
                        @else
                            <div class="mt-4">
                                <form action="{{ route('student.quizzes.start', $quiz) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Mulai Mengerjakan Kuis
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Attempts -->
        @if($attempts->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Upaya</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attempts as $index => $attempt)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $index + 1 }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $attempt->start_time->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $attempt->end_time ? $attempt->end_time->format('d M Y, H:i') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($attempt->status === 'completed') 
                                                    bg-green-100 text-green-800
                                                @elseif($attempt->status === 'in_progress')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                @if($attempt->status === 'completed')
                                                    Selesai
                                                @elseif($attempt->status === 'in_progress')
                                                    Sedang Dikerjakan
                                                @else
                                                    Dibatalkan
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $attempt->score !== null ? number_format($attempt->score, 2) : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($attempt->status === 'in_progress')
                                                <a href="{{ route('student.quizzes.attempt', $attempt) }}" class="text-amber-600 hover:text-amber-900">
                                                    Lanjutkan
                                                </a>
                                            @elseif($attempt->status === 'completed')
                                                <a href="{{ route('student.quizzes.review', $attempt) }}" class="text-blue-600 hover:text-blue-900">
                                                    Review
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-student-layout>