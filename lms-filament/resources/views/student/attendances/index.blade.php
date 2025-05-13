<!-- resources/views/student/attendances/index.blade.php -->
<x-student-layout>
    <x-slot name="title">Presensi</x-slot>
    <x-slot name="header">Presensi</x-slot>
    
    <div class="space-y-6">
        <!-- Attendance Stats -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Presensi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($attendanceByCourse as $courseId => $data)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ $data['course']->title }}</h4>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Persentase Kehadiran:</p>
                                <p class="font-medium 
                                    @if($data['percentage'] >= 80)
                                        text-green-600 dark:text-green-400
                                    @elseif($data['percentage'] >= 60)
                                        text-yellow-600 dark:text-yellow-400
                                    @else
                                        text-red-600 dark:text-red-400
                                    @endif
                                ">
                                    {{ number_format($data['percentage'], 1) }}%
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Hadir:</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $data['present'] }}/{{ $data['total'] }}</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('student.attendances.course', $data['course']) }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Lihat Detail &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Recent Attendances -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Riwayat Presensi Terbaru</h3>
                
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mata Kuliah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->session_date->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($attendance->status === 'hadir')
                                                    bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @elseif($attendance->status === 'izin')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                @elseif($attendance->status === 'sakit')
                                                    bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                @endif
                                            ">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->notes ?: '-' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Belum ada riwayat presensi.</p>
                @endif
            </div>
        </div>
    </div>
</x-student-layout>