<!-- resources/views/student/quizzes/attempt.blade.php -->
<x-student-layout>
    <x-slot name="title">{{ $quiz->title }} - Pengerjaan</x-slot>
    <x-slot name="header">{{ $quiz->title }} - Pengerjaan</x-slot>
    
    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Kuis</h3>
                    
                    <div class="bg-gray-100 py-2 px-4 rounded-lg text-gray-700 font-medium">
                        Sisa Waktu: <span id="timer" class="font-bold">{{ gmdate('H:i:s', max(0, $timeLeft)) }}</span>
                    </div>
                </div>
                
                <div class="mt-2">
                    <p class="text-sm text-gray-600">
                        Jumlah Soal: {{ $questions->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <form id="quizForm" action="{{ route('student.quizzes.submit', $attempt) }}" method="POST">
            @csrf
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Navigasi Soal</h3>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($questions as $index => $navQuestion)
                            <button type="button" class="question-nav-btn w-10 h-10 flex items-center justify-center rounded-md border 
                                @if(isset($answers[$navQuestion->id])) 
                                    bg-green-100 border-green-500 text-green-800
                                @else
                                    bg-gray-100 border-gray-300 text-gray-700
                                @endif
                            " data-question-id="{{ $navQuestion->id }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div id="questions-container">
                @foreach($questions as $index => $question)
                    <div class="question-container bg-white overflow-hidden shadow-sm rounded-lg mb-6 {{ $index > 0 ? 'hidden' : '' }}" data-question-id="{{ $question->id }}">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Soal {{ $index + 1 }}</h3>
                                <div class="text-sm text-gray-500">
                                    {{ $index + 1 }} dari {{ $questions->count() }}
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <div class="prose max-w-none text-gray-700">
                                    {{ $question->question_text }}
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                @if($question->question_type === 'essay')
                                    <label for="answer-{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-2">Jawaban Anda:</label>
                                    <textarea id="answer-{{ $question->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-500 focus:ring-opacity-50" rows="5" data-question-id="{{ $question->id }}">{{ isset($answers[$question->id]) ? $answers[$question->id] : '' }}</textarea>
                                @else
                                    <fieldset>
                                        <legend class="text-sm font-medium text-gray-700 mb-2">Pilih jawaban yang benar:</legend>
                                        <div class="space-y-2">
                                            @foreach($question->options as $option)
                                                <div class="flex items-center">
                                                    <input 
                                                        id="option-{{ $option->id }}" 
                                                        name="answer-{{ $question->id }}" 
                                                        type="radio" 
                                                        value="{{ $option->id }}"
                                                        class="h-4 w-4 border-gray-300 text-amber-600 focus:ring-amber-500"
                                                        data-question-id="{{ $question->id }}"
                                                        {{ isset($answers[$question->id]) && $answers[$question->id] == $option->id ? 'checked' : '' }}
                                                    >
                                                    <label for="option-{{ $option->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif
                            </div>
                            
                            <div class="flex justify-between">
                                <button type="button" class="prev-btn inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 {{ $index === 0 ? 'invisible' : '' }}">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Soal Sebelumnya
                                </button>
                                
                                <button type="button" class="next-btn inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150 {{ $index === $questions->count() - 1 ? 'invisible' : '' }}">
                                    Soal Berikutnya
                                    <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        Total Soal Terjawab: <span id="answered-count">{{ count($answers) }}</span> dari {{ $questions->count() }}
                    </p>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Selesaikan Kuis
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer
            const timerElement = document.getElementById('timer');
            let timeLeft = {{ $timeLeft }};
            
            const timer = setInterval(function() {
                timeLeft--;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    document.getElementById('quizForm').submit();
                    return;
                }
                
                const hours = Math.floor(timeLeft / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;
                
                timerElement.textContent = 
                    (hours < 10 ? '0' + hours : hours) + ':' + 
                    (minutes < 10 ? '0' + minutes : minutes) + ':' + 
                    (seconds < 10 ? '0' + seconds : seconds);
            }, 1000);
            
            // Navigation
            const questionContainers = document.querySelectorAll('.question-container');
            const navButtons = document.querySelectorAll('.question-nav-btn');
            const prevButtons = document.querySelectorAll('.prev-btn');
            const nextButtons = document.querySelectorAll('.next-btn');
            
            navButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.questionId;
                    
                    questionContainers.forEach(container => {
                        container.classList.add('hidden');
                    });
                    
                    document.querySelector(`.question-container[data-question-id="${questionId}"]`).classList.remove('hidden');
                });
            });
            
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentContainer = this.closest('.question-container');
                    const currentIndex = Array.from(questionContainers).indexOf(currentContainer);
                    
                    if (currentIndex > 0) {
                        questionContainers.forEach(container => {
                            container.classList.add('hidden');
                        });
                        
                        questionContainers[currentIndex - 1].classList.remove('hidden');
                    }
                });
            });
            
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentContainer = this.closest('.question-container');
                    const currentIndex = Array.from(questionContainers).indexOf(currentContainer);
                    
                    if (currentIndex < questionContainers.length - 1) {
                        questionContainers.forEach(container => {
                            container.classList.add('hidden');
                        });
                        
                        questionContainers[currentIndex + 1].classList.remove('hidden');
                    }
                });
            });
            
            // Save answers
            function saveAnswer(questionId, answer) {
                const answeredCount = document.getElementById('answered-count');
                const navButton = document.querySelector(`.question-nav-btn[data-question-id="${questionId}"]`);
                
                fetch('{{ route("student.quizzes.saveAnswer", $attempt) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer: answer
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        navButton.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
                        navButton.classList.add('bg-green-100', 'dark:bg-green-800', 'border-green-500', 'dark:border-green-600', 'text-green-800', 'dark:text-green-200');
                        
                        // Update answered count
                        const currentAnswers = document.querySelectorAll('.bg-green-100, .dark\\:bg-green-800');
                        answeredCount.textContent = currentAnswers.length;
                    }
                })
                .catch(error => {
                    console.error('Error saving answer:', error);
                });
            }
            
            // Save answer on radio button change
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionId = this.dataset.questionId;
                    saveAnswer(questionId, this.value);
                });
            });
            
            // Save answer on textarea change (debounced)
            let textareaTimeout;
            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    const questionId = this.dataset.questionId;
                    
                    clearTimeout(textareaTimeout);
                    textareaTimeout = setTimeout(() => {
                        saveAnswer(questionId, this.value);
                    }, 500);
                });
            });
        });
    </script>
    @endpush
</x-student-layout>