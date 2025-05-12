<?php
// app/Filament/Pages/Dashboard.php

namespace App\Filament\Pages;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';
    
    protected function getHeaderWidgets(): array
    {
        // Jika user adalah admin, tampilkan semua statistik
        if (Auth::user()->isAdmin()) {
            return [
                \App\Filament\Widgets\CourseStats::class,
                \App\Filament\Widgets\UpcomingAssignments::class,
                \App\Filament\Widgets\RecentSubmissions::class,
            ];
        }
        
        // Jika user adalah dosen, tampilkan statistik khusus dosen
        if (Auth::user()->isTeacher()) {
            return [
                \App\Filament\Widgets\CourseStats::class,
                \App\Filament\Widgets\UpcomingAssignments::class,
                \App\Filament\Widgets\RecentSubmissions::class,
            ];
        }
        
        // Jika user adalah mahasiswa, tampilkan pesan akses ditolak
        return [];
    }
}