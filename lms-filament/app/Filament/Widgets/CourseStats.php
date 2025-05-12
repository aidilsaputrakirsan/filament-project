<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kursus', Course::count())
                ->description('Jumlah kursus yang tersedia')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
                
            Stat::make('Total Siswa', User::where('role', 'student')->count())
                ->description('Jumlah siswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
                
            Stat::make('Total Pendaftaran', Enrollment::count())
                ->description('Jumlah pendaftaran kursus')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),
        ];
    }
}