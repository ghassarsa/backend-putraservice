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
use App\Models\Category;
use Filament\Forms\Components\Select;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('image-')
                            ->replace(' ', '-')
                            ->lower()
                            ->append('-' . now()->timestamp . '.webp'),
                    )
                    ->storeFileNamesIn('original_filename')
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $set) {
                        // Generate filename
                        $filename = (string) str($file->getClientOriginalName())
                            ->prepend('image-')
                            ->replace(' ', '-')
                            ->lower()
                            ->append('-' . now()->timestamp . '.webp');

                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($file->getRealPath());

                        $image->resize(1920, 1080);

                        $webpData = $image->encodeByExtension('webp', 80);

                        $path = 'uploads/image/' . $filename;
                        Storage::disk('public')->put($path, $webpData);

                        return $path;
                    })
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->nullable(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title'),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->width(100)
                    ->height(60),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('category.name')->label('Category'),
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
