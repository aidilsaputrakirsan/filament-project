<?php
namespace App\Filament\Resources;

use App\Filament\Resources\MataKuliahResource\Pages;
use App\Models\MataKuliah;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'Akademik';
    
    protected static ?string $navigationLabel = 'Mata Kuliah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('user_id')
                    ->label('Pengajar')
                    ->options(User::where('role', 'dosen')->pluck('name', 'id'))
                    ->required()
                    ->visible(fn () => Auth::user()->isAdmin()),
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
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengajar')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Dipublikasikan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Filter berdasarkan pengajar'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status publikasi'),
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
        
        // Admin dapat melihat semua kursus
        if (Auth::user()->isAdmin()) {
            return $query;
        }
        
        // Dosen hanya dapat melihat kursus yang dibuat olehnya
        if (Auth::user()->isTeacher()) {
            return $query->where('user_id', Auth::id());
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
            'index' => Pages\ListMataKuliah::route('/'),
            'create' => Pages\CreateMataKuliah::route('/create'),
            'edit' => Pages\EditMataKuliah::route('/{record}/edit'),
        ];
    }
}