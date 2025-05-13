<!-- resources/views/student/grades/index.blade.php -->
<x-student-layout>
    <x-slot name="title">Nilai</x-slot>
    <x-slot name="header">Nilai</x-slot>
    
    <div class="space-y-6">
        @foreach($courseGrades as $courseGrade)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $courseGrade['course']->title }}</h3>
                        <a href="{{ route('student.grades.course', $courseGrade['course']) }}" class="text-sm text-amber-600 hover:underline">Lihat Detail</a>
                    </div>
                    
                    <!-- Assignment Grades -->
                    @if(count($courseGrade['assignments']) > 0)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Nilai Tugas</h4>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($courseGrade['assignments'] as $assignment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $assignment['assignment']->title }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($assignment['submission'])
                                                        @if($assignment['score'] !== null)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Dinilai
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Menunggu Penilaian
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Tidak Dikumpulkan
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($assignment['score'] !== null)
                                                        <div class="text-sm text-gray-900">
                                                            {{ $assignment['score'] }}/{{ $assignment['max_score'] }}
                                                            ({{ number_format(($assignment['score'] / $assignment['max_score']) * 100, 1) }}%)
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">-</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quiz Grades -->
                    @if(count($courseGrade['quizzes']) > 0)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Nilai Kuis & Test</h4>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuis</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($courseGrade['quizzes'] as $quiz)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $quiz['quiz']->title }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        @if($quiz['quiz']->quiz_type === 'pre_test')
                                                            Pre-Test
                                                        @elseif($quiz['quiz']->quiz_type === 'post_test')
                                                            Post-Test
                                                        @else
                                                            Kuis
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($quiz['attempt'])
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Tidak Diikuti
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($quiz['score'] !== null)
                                                        <div class="text-sm text-gray-900">
                                                            {{ number_format($quiz['score'], 2) }}/100
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">-</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-student-layout>