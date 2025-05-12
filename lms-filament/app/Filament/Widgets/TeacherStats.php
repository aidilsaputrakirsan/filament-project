<?php
// app/Filament/Widgets/TeacherStats.php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TeacherStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        
        return [
            Stat::make('Mata Kuliah Saya', Course::where('user_id', $userId)->count())
                ->description('Jumlah mata kuliah yang Anda ajarkan')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Total Mahasiswa', Enrollment::whereHas('course', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count())
                ->description('Jumlah mahasiswa terdaftar di kelas Anda')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('Total Tugas', Assignment::whereHas('course', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count())
                ->description('Jumlah tugas yang Anda buat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger'),
                
            Stat::make('Total Kuis', Quiz::whereHas('course', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count())
                ->description('Jumlah kuis yang Anda buat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
                
            Stat::make('Pengumpulan Tugas', Submission::whereHas('assignment.course', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count())
                ->description('Jumlah pengumpulan tugas oleh mahasiswa')
                ->descriptionIcon('heroicon-m-document-arrow-up')
                ->color('success'),
                
            Stat::make('Tugas Belum Dinilai', Submission::whereHas('assignment.course', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->whereNull('score')->count())
                ->description('Jumlah tugas yang belum dinilai')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}