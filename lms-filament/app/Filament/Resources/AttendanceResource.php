<?php
// app/Filament/Resources/AttendanceResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\MataKuliah;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Presensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->options(function () {
                        // Admin dapat memilih semua kursus
                        if (Auth::user()->isAdmin()) {
                            return Course::all()->pluck('title', 'id');
                        }
                        
                        // Dosen hanya dapat memilih kursus yang dibuat olehnya
                        return Course::where('user_id', Auth::id())->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('user_id', null)),

                Forms\Components\Select::make('user_id')
                    ->label('Mahasiswa')
                    ->options(function (callable $get) {
                        $courseId = $get('course_id');
                        if (!$courseId) {
                            return User::where('role', 'student')->pluck('name', 'id');
                        }
                        
                        return User::whereHas('enrollments', function (Builder $query) use ($courseId) {
                            $query->where('course_id', $courseId);
                        })->where('role', 'student')->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\DateTimePicker::make('session_date')
                    ->label('Tanggal Sesi')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa (Tanpa Keterangan)',
                    ])
                    ->required()
                    ->default('hadir'),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Mata Kuliah')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Mahasiswa')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('session_date')
                    ->label('Tanggal Sesi')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'info' => 'sakit',
                        'danger' => 'alpa',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'hadir' => 'Hadir',
                            'izin' => 'Izin',
                            'sakit' => 'Sakit',
                            'alpa' => 'Alpa',
                            default => $state,
                        };
                    }),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Mata Kuliah')
                    ->options(function () {
                        // Admin dapat memilih semua kursus
                        if (Auth::user()->isAdmin()) {
                            return Course::all()->pluck('title', 'id');
                        }
                        
                        // Dosen hanya dapat memilih kursus yang dibuat olehnya
                        return Course::where('user_id', Auth::id())->pluck('title', 'id');
                    })
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa',
                    ]),
                    
                Tables\Filters\Filter::make('session_date')
                    ->form([
                        Forms\Components\DatePicker::make('session_date_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('session_date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['session_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('session_date', '>=', $date),
                            )
                            ->when(
                                $data['session_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('session_date', '<=', $date),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Admin dapat melihat semua presensi
        if (Auth::user()->isAdmin()) {
            return $query;
        }
        
        // Dosen hanya dapat melihat presensi untuk kursus yang dibuat olehnya
        if (Auth::user()->isTeacher()) {
            return $query->whereHas('course', function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
        }
        
        return $query;
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}