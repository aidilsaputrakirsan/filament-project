<?php
// app/Filament/Resources/QuizResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Kuis & Test';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable(),
                    
                Forms\Components\Select::make('quiz_type')
                    ->label('Jenis')
                    ->options([
                        'pre_test' => 'Pre-Test',
                        'post_test' => 'Post-Test',
                        'quiz' => 'Kuis',
                    ])
                    ->required()
                    ->default('quiz'),
                    
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->required()
                    ->after('start_time'),
                    
                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Durasi (menit)')
                    ->numeric()
                    ->required()
                    ->default(60)
                    ->minValue(1),
                    
                Forms\Components\Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(false),
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
                    
                Tables\Columns\BadgeColumn::make('quiz_type')
                    ->label('Jenis')
                    ->colors([
                        'primary' => 'quiz',
                        'success' => 'pre_test',
                        'danger' => 'post_test',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pre_test' => 'Pre-Test',
                        'post_test' => 'Post-Test',
                        'quiz' => 'Kuis',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Mulai')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Selesai')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Dipublikasikan')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Jumlah Soal')
                    ->counts('questions'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'title'),
                    
                Tables\Filters\SelectFilter::make('quiz_type')
                    ->label('Jenis')
                    ->options([
                        'pre_test' => 'Pre-Test',
                        'post_test' => 'Post-Test',
                        'quiz' => 'Kuis',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),
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
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}