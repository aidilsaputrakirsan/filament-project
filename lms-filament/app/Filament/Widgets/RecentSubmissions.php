<?php
// app/Filament/Widgets/RecentSubmissions.php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSubmissions extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pengumpulan Tugas Terbaru')
            ->query(
                Submission::query()
                    ->whereNull('score')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('assignment.title')
                    ->label('Tugas'),
                    
                Tables\Columns\TextColumn::make('assignment.course.title')
                    ->label('Mata Kuliah'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pengumpulan')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($record) => $record->score === null ? 'Belum dinilai' : 'Sudah dinilai')
                    ->colors([
                        'warning' => fn ($state) => $state === 'Belum dinilai',
                        'success' => fn ($state) => $state === 'Sudah dinilai',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('grade')
                    ->label('Nilai')
                    ->url(fn (Submission $record): string => route('filament.admin.resources.assignments.edit', $record->assignment))
                    ->icon('heroicon-o-pencil'),
            ]);
    }
}