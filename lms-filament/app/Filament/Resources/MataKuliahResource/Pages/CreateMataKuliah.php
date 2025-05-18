<?php

namespace App\Filament\Resources\MataKuliahResource\Pages;

use App\Filament\Resources\MataKuliahResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMataKuliah extends CreateRecord
{
    protected static string $resource = MataKuliahResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika user_id tidak diisi, gunakan ID user yang login
        if (!isset($data['user_id']) && auth()->check()) {
            $data['user_id'] = auth()->id();
        }
        
        return $data;
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Mata kuliah berhasil dibuat';
    }
}