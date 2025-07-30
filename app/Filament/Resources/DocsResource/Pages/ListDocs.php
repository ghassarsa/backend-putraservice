<?php

namespace App\Filament\Resources\DocsResource\Pages;

use App\Filament\Resources\DocsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocs extends ListRecords
{
    protected static string $resource = DocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
