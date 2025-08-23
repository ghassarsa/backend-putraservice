<?php

namespace App\Filament\Resources\SetDocsResource\Pages;

use App\Filament\Resources\SetDocsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSetDocs extends ListRecords
{
    protected static string $resource = SetDocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
