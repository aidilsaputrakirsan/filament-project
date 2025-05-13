<!-- resources/views/student/attendances/index.blade.php -->
<x-student-layout>
    <x-slot name="title">Presensi</x-slot>
    <x-slot name="header">Presensi</x-slot>
    
    <div class="space-y-6">
        <!-- Attendance Stats -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Presensi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($attendanceByCourse as $courseId => $data)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $data['course']->title }}</h4>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500">Persentase Kehadiran:</p>
                                <p class="font-medium 
                                    @if($data['percentage'] >= 80)
                                        text-green-600
                                    @elseif($data['percentage'] >= 60)
                                        text-yellow-600
                                    @else
                                        text-red-600
                                    @endif
                                ">
                                    {{ number_format($data['percentage'], 1) }}%
                                </p>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500">Jumlah Hadir:</p>
                                <p class="font-medium text-gray-900">{{ $data['present'] }}/{{ $data['total'] }}</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('student.attendances.course', $data['course']) }}" class="text-sm text-amber-600 hover:underline">Lihat Detail &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Recent Attendances -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Presensi Terbaru</h3>
                
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $attendance->session_date->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $attendance->course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($attendance->status === 'hadir')
                                                    bg-green-100 text-green-800
                                                @elseif($attendance->status === 'izin')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($attendance->status === 'sakit')
                                                    bg-blue-100 text-blue-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500">{{ $attendance->notes ?: '-' }}</div>
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
                    <p class="text-gray-600">Belum ada riwayat presensi.</p>
                @endif
            </div>
        </div>
    </div>
</x-student-layout>