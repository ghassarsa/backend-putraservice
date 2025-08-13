<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canEdit($record): bool {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Textarea::make('komentar')
                ->label('Komentar')
                ->required()
                ->disabled(),

            TextInput::make('rating')
                ->label('Rating')
                ->numeric()
                ->minValue(0)
                ->maxValue(5)
                ->step(0.1)
                ->required()
                ->disabled(),

            Select::make('validasi')
                ->label('Status Validasi')
                ->options([
                    'belum' => 'Belum',
                    'sudah' => 'Sudah',
                ])
                ->required()
                ->disabled(),

            Select::make('perubahan')
                ->label('Perubahan')
                ->options([
                    'tidak' => 'Tidak',
                    'ya' => 'Ya',
                ])
                ->required()
                ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('komentar')->limit(50)->wrap(),
                TextColumn::make('rating'),
                TextColumn::make('validasi')->badge()->color(fn ($state) => $state === 'sudah' ? 'success' : 'warning'),
                TextColumn::make('perubahan')->badge()->color(fn ($state) => $state === 'ya' ? 'info' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                            Action::make('toggleValidasi')
                ->label(fn ($record) => $record->validasi === 'sudah' ? 'Set Belum' : 'Set Sudah')
                ->action(function ($record) {
                    $record->validasi = $record->validasi === 'sudah' ? 'belum' : 'sudah';
                    $record->save();
                })
                ->color(fn ($record) => $record->validasi === 'sudah' ? 'success' : 'warning')
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
