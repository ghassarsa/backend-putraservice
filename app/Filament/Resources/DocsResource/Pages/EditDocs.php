<?php

namespace App\Filament\Resources\DocsResource\Pages;

use App\Filament\Resources\DocsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocs extends EditRecord
{
    protected static string $resource = DocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
