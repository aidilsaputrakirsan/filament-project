<?php

namespace App\Filament\Resources\ImportExportTemplateResource\Pages;

use App\Filament\Resources\ImportExportTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportExportTemplate extends EditRecord
{
    protected static string $resource = ImportExportTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
