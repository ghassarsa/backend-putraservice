<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocsResource\Pages;
use App\Filament\Resources\DocsResource\RelationManagers;
use App\Models\Docs;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocsResource extends Resource
{
    protected static ?string $model = Docs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('uploads/image')
                    ->maxSize(2048)
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('image')->label('Image'),
                TextColumn::make('description')->label('Description'),
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
            'index' => Pages\ListDocs::route('/'),
            'create' => Pages\CreateDocs::route('/create'),
            'edit' => Pages\EditDocs::route('/{record}/edit'),
        ];
    }
}
