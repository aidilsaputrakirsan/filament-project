<?php
// app/Filament/Resources/QuizResource/RelationManagers/QuestionsRelationManager.php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $recordTitleAttribute = 'question_text';
    protected static ?string $title = 'Soal';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question_text')
                    ->label('Pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('question_type')
                    ->label('Jenis Pertanyaan')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar/Salah',
                        'essay' => 'Essay',
                    ])
                    ->required()
                    ->default('multiple_choice')
                    ->reactive(),
                    
                Forms\Components\TextInput::make('points')
                    ->label('Poin')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1),
                    
                Forms\Components\Section::make('Opsi Jawaban')
                    ->schema([
                        Forms\Components\Repeater::make('options')
                            ->label('Opsi')
                            ->relationship('options')
                            ->schema([
                                Forms\Components\Textarea::make('option_text')
                                    ->label('Teks Opsi')
                                    ->required(),
                                    
                                Forms\Components\Checkbox::make('is_correct')
                                    ->label('Jawaban Benar'),
                            ])
                            ->columns(2)
                            ->defaultItems(4)
                            ->minItems(2)
                            ->maxItems(function (callable $get) {
                                return $get('question_type') === 'true_false' ? 2 : 6;
                            })
                            ->hidden(fn (callable $get) => $get('question_type') === 'essay'),
                    ])
                    ->hidden(fn (callable $get) => $get('question_type') === 'essay'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                    
                Tables\Columns\TextColumn::make('question_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar/Salah',
                        'essay' => 'Essay',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'multiple_choice',
                        'success' => 'true_false',
                        'warning' => 'essay',
                    ]),
                    
                Tables\Columns\TextColumn::make('points')
                    ->label('Poin')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('options_count')
                    ->label('Jumlah Opsi')
                    ->counts('options')
                    ->hidden(fn ($record) => $record->question_type === 'essay'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('question_type')
                    ->label('Jenis Pertanyaan')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar/Salah',
                        'essay' => 'Essay',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}