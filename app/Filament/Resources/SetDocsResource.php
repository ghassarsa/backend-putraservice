<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetDocsResource\Pages;
use App\Filament\Resources\SetDocsResource\RelationManagers;
use App\Models\SetDocs;
use App\Models\settings_docs;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SetDocsResource extends Resource
{
    protected static ?string $model = settings_docs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->label('Jumlah Docs')
                    ->required()
                    ->numeric(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Key'),
                TextColumn::make('value')->label('Value'),
                TextColumn::make('category.name')->label('Kategori')->default('Global'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSetDocs::route('/'),
            'create' => Pages\CreateSetDocs::route('/create'),
            'edit' => Pages\EditSetDocs::route('/{record}/edit'),
        ];
    }
}
