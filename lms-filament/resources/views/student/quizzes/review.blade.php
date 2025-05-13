<!-- resources/views/student/quizzes/review.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $quiz->title }} - Review</x-slot>
    <x-slot name="header">{{ $quiz->title }} - Review</x-slot>
    
    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="mb-4">
                    <a href="{{ route('student.quizzes.show', $quiz) }}" class="text-amber-600 hover:underline flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke detail kuis
                    </a>
                </div>
                
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Hasil Kuis</h3>
                </div>
                
                <div class="mt-4">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Waktu Mulai</p>
                                <p class="font-medium text-gray-900">{{ $attempt->start_time->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu Selesai</p>
                                <p class="font-medium text-gray-900">{{ $attempt->end_time->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Durasi</p>
                                <p class="font-medium text-gray-900">{{ $attempt->start_time->diffInMinutes($attempt->end_time) }} menit</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nilai</p>
                                <p class="font-medium text-gray-900">{{ number_format($attempt->score, 2) }} / 100</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="font-medium text-green-600">Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Review Jawaban</h3>
                
                <div class="space-y-6">
                    @foreach($questions as $index => $question)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-md font-medium text-gray-900">Soal {{ $index + 1 }}</h4>
                                
                                @php
                                    $answer = $answers[$question->id] ?? null;
                                    $isCorrect = false;
                                    
                                    if ($question->question_type !== 'essay' && $answer && $answer->questionOption && $answer->questionOption->is_correct) {
                                        $isCorrect = true;
                                    }
                                @endphp
                                
                                @if($question->question_type !== 'essay')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($answer && $isCorrect)
                                            bg-green-100 text-green-800
                                        @elseif($answer)
                                            bg-red-100 text-red-800
                                        @else
                                            bg-yellow-100 text-yellow-800
                                        @endif
                                    ">
                                        @if($answer && $isCorrect)
                                            Benar
                                        @elseif($answer)
                                            Salah
                                        @else
                                            Tidak Dijawab
                                        @endif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Esai
                                    </span>
                                @endif
                            </div>
                            
                            <div class="prose max-w-none mb-4 text-gray-700">
                                {{ $question->question_text }}
                            </div>
                            
                            @if($question->question_type === 'essay')
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-1">Jawaban Anda:</h5>
                                    <div class="bg-gray-50 p-3 rounded-md text-gray-700">
                                        {{ $answer ? $answer->answer_text : 'Tidak dijawab' }}
                                    </div>
                                </div>
                                
                                @if($answer && $answer->score !== null)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Nilai:</h5>
                                        <div class="text-gray-700">
                                            {{ $answer->score }} / {{ $question->points }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-1">Pilihan:</h5>
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <div class="flex items-center">
                                                <span class="h-4 w-4 mr-3 
                                                    @if($answer && $answer->questionOption && $answer->questionOption->id === $option->id)
                                                        @if($option->is_correct)
                                                            text-green-600
                                                        @else
                                                            text-red-600
                                                        @endif
                                                    @elseif($option->is_correct)
                                                        text-green-600
                                                    @else
                                                        text-gray-400
                                                    @endif
                                                ">
                                                    @if($answer && $answer->questionOption && $answer->questionOption->id === $option->id)
                                                        @if($option->is_correct)
                                                            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                    @elseif($option->is_correct)
                                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span class="text-sm 
                                                    @if($answer && $answer->questionOption && $answer->questionOption->id === $option->id)
                                                        @if($option->is_correct)
                                                            font-medium text-green-700
                                                        @else
                                                            font-medium text-red-700
                                                        @endif
                                                    @elseif($option->is_correct)
                                                        font-medium text-green-700
                                                    @else
                                                        text-gray-700
                                                    @endif
                                                ">
                                                    {{ $option->option_text }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-student-layout>