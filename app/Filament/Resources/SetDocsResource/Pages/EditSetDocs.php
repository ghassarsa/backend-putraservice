<?php

namespace App\Filament\Resources\SetDocsResource\Pages;

use App\Filament\Resources\SetDocsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetDocs extends EditRecord
{
    protected static string $resource = SetDocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
