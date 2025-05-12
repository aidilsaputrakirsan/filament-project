<?php
// app/Filament/Widgets/UpcomingAssignments.php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingAssignments extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Tugas yang Akan Datang')
            ->query(
                Assignment::query()
                    ->where('due_date', '>', now())
                    ->orderBy('due_date', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Mata Kuliah'),
                    
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Batas Waktu')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('Pengumpulan / Total Siswa')
                    ->formatStateUsing(function ($record) {
                        $submissions = $record->submissions()->count();
                        $totalStudents = $record->course->students()->count();
                        return "{$submissions} / {$totalStudents}";
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (Assignment $record): string => route('filament.admin.resources.assignments.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}