<?php
// app/Filament/Pages/AccessDenied.php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AccessDenied extends Page
{
    protected static ?string $title = 'Akses Ditolak';
    
    protected static string $view = 'filament.pages.access-denied';
    
    protected static bool $shouldRegisterNavigation = false;
}