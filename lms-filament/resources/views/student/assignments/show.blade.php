<!-- resources/views/student/assignments/show.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $assignment->title }}</x-slot>
    <x-slot name="header">{{ $assignment->title }}</x-slot>
    
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
        
        <!-- Assignment Details -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="mb-4">
                    <a href="{{ route('student.assignments.index') }}" class="text-amber-600 hover:underline flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke daftar tugas
                    </a>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Tugas</h3>
                    <p class="text-sm text-gray-600 mt-2">Mata Kuliah: {{ $assignment->course->title }}</p>
                    <p class="text-sm text-gray-600 mt-1">Batas Waktu: {{ $assignment->due_date->format('d M Y, H:i') }}</p>
                    <p class="text-sm text-gray-600 mt-1">Nilai Maksimum: {{ $assignment->max_score }}</p>
                    
                    @if($assignment->attachment)
                        <div class="mt-4">
                            <a href="{{ asset('storage/' . $assignment->attachment) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Download Lampiran
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Deskripsi</h4>
                    <div class="text-gray-600 prose max-w-none">
                        {!! $assignment->description !!}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submission Form or Submission Details -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    @if($submission)
                        Detail Pengumpulan
                    @else
                        Formulir Pengumpulan
                    @endif
                </h3>
                
                @if($submission)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            Dikumpulkan pada: {{ $submission->created_at->format('d M Y, H:i') }}
                            @if($submission->created_at != $submission->updated_at)
                                (Diperbarui pada: {{ $submission->updated_at->format('d M Y, H:i') }})
                            @endif
                        </p>
                        
                        @if($submission->score !== null)
                            <div class="mt-2 p-3 bg-green-100 rounded-md">
                                <p class="font-medium text-green-800">
                                    Nilai: {{ $submission->score }}/{{ $assignment->max_score }}
                                </p>
                                @if($submission->feedback)
                                    <p class="mt-2 text-green-700">
                                        Feedback: {{ $submission->feedback }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="mt-2 text-yellow-600">
                                Status: Menunggu penilaian
                            </p>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-900 mb-2">Konten Pengumpulan</h4>
                        <div class="text-gray-600 prose max-w-none p-4 bg-gray-50 rounded-md">
                            {!! $submission->content !!}
                        </div>
                    </div>
                    
                    @if($submission->file_path)
                        <div class="mb-4">
                            <h4 class="text-md font-medium text-gray-900 mb-2">File Pengumpulan</h4>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Download File
                            </a>
                        </div>
                    @endif
                    
                    @if($assignment->due_date > now())
                        <div class="mt-6">
                            <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h4 class="text-md font-medium text-gray-900 mb-2">Perbarui Pengumpulan</h4>
                                
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                                    <textarea id="content" name="content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50" required>{{ old('content', $submission->content) }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700">File (Opsional)</label>
                                    <input id="file" name="file" type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50">
                                    @if($submission->file_path)
                                        <p class="mt-1 text-sm text-gray-500">
                                            File saat ini: {{ basename($submission->file_path) }}
                                        </p>
                                    @endif
                                    @error('file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Perbarui Pengumpulan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-yellow-100 rounded-md">
                            <p class="text-yellow-800">
                                Batas waktu pengumpulan telah berakhir. Anda tidak dapat melakukan pembaruan.
                            </p>
                        </div>
                    @endif
                @else
                    @if($assignment->due_date < now())
                        <div class="p-3 bg-red-100 rounded-md">
                            <p class="text-red-800">
                                Batas waktu pengumpulan telah berakhir. Anda tidak dapat mengumpulkan tugas ini.
                            </p>
                        </div>
                    @else
                        <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                                <textarea id="content" name="content" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700">File (Opsional)</label>
                                <input id="file" name="file" type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50">
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Kumpulkan Tugas
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-student-layout>