<?php
// app/Filament/Resources/AssignmentResource/RelationManagers/SubmissionsRelationManager.php

namespace App\Filament\Resources\AssignmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Pengumpulan Tugas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Mahasiswa')
                    ->relationship('user', 'name')
                    ->required(),
                    
                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\FileUpload::make('file_path')
                    ->label('File')
                    ->directory('submissions')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'application/zip'])
                    ->maxSize(10240), // 10MB
                    
                Forms\Components\TextInput::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(function (RelationManager $livewire): int {
                        return $livewire->ownerRecord->max_score;
                    }),
                    
                Forms\Components\Textarea::make('feedback')
                    ->label('Feedback')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pengumpulan')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('file_path')
                    ->label('File')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark'),
                    
                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state, $record) => $state !== null ? "{$state}/{$record->assignment->max_score}" : 'Belum dinilai'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Mahasiswa')
                    ->relationship('user', 'name'),
                    
                Tables\Filters\Filter::make('scored')
                    ->label('Status Penilaian')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('score'))
                    ->toggle(),
                    
                Tables\Filters\Filter::make('not_scored')
                    ->label('Belum Dinilai')
                    ->query(fn (Builder $query): Builder => $query->whereNull('score'))
                    ->toggle(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download File')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->file_path ? url("storage/{$record->file_path}") : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->file_path),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}