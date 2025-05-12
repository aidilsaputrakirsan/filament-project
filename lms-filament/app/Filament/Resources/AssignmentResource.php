<?php
// app/Filament/Resources/AssignmentResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Filament\Resources\AssignmentResource\RelationManagers;
use App\Models\Assignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Tugas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'title')
                    ->required()
                    ->preload()
                    ->searchable(),
                    
                Forms\Components\DateTimePicker::make('due_date')
                    ->label('Batas Waktu')
                    ->required(),
                    
                Forms\Components\TextInput::make('max_score')
                    ->label('Nilai Maksimum')
                    ->required()
                    ->numeric()
                    ->default(100),
                    
                Forms\Components\FileUpload::make('attachment')
                    ->label('Lampiran')
                    ->directory('assignments')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'])
                    ->maxSize(5120), // 5MB
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Mata Kuliah')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Batas Waktu')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('max_score')
                    ->label('Nilai Maksimum')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('Jumlah Pengumpulan')
                    ->counts('submissions'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'title'),
                    
                Tables\Filters\Filter::make('due_date')
                    ->form([
                        Forms\Components\DatePicker::make('due_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('due_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '>=', $date),
                            )
                            ->when(
                                $data['due_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}