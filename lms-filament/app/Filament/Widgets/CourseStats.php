<?php
// app/Filament/Widgets/CourseStats.php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Mata Kuliah', Course::count())
                ->description('Jumlah mata kuliah yang tersedia')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Total Mahasiswa', User::where('role', 'student')->count())
                ->description('Jumlah mahasiswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('Total Pendaftaran', Enrollment::count())
                ->description('Jumlah pendaftaran kursus')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),
                
            Stat::make('Total Tugas', Assignment::count())
                ->description('Jumlah tugas yang dibuat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger'),
                
            Stat::make('Total Kuis', Quiz::count())
                ->description('Jumlah kuis yang dibuat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
                
            Stat::make('Pengumpulan Tugas', Submission::count())
                ->description('Jumlah pengumpulan tugas')
                ->descriptionIcon('heroicon-m-document-arrow-up')
                ->color('success'),
        ];
    }
}