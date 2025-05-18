<?php

// app/Filament/Resources/ImportExportTemplateResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ImportExportTemplateResource\Pages;
use App\Models\ImportExportTemplate;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImportExportTemplateResource extends Resource
{
    protected static ?string $model = ImportExportTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationGroup = 'Import/Export';
    protected static ?string $navigationLabel = 'Template';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Template'),
                
                Select::make('type')
                    ->options([
                        'import' => 'Import',
                        'export' => 'Export',
                    ])
                    ->required()
                    ->label('Tipe'),
                
                TextInput::make('description')
                    ->maxLength(255)
                    ->label('Deskripsi'),
                
                FileUpload::make('file_path')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->directory('import-export-templates')
                    ->required()
                    ->label('File Template'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'import' ? 'primary' : 'success')
                    ->label('Tipe'),
                
                TextColumn::make('description')
                    ->limit(50)
                    ->label('Deskripsi'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (ImportExportTemplate $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImportExportTemplates::route('/'),
            'create' => Pages\CreateImportExportTemplate::route('/create'),
            'edit' => Pages\EditImportExportTemplate::route('/{record}/edit'),
        ];
    }
}