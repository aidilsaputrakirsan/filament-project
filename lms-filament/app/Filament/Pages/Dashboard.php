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
    // Sementara tidak menampilkan widget sampai fitur lengkap
    return [];
}
}