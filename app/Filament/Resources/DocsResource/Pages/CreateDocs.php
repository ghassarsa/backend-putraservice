<?php

namespace App\Filament\Resources\DocsResource\Pages;

use App\Filament\Resources\DocsResource;
use App\Models\Docs;
use Filament\Resources\Pages\CreateRecord;

class CreateDocs extends CreateRecord
{
    protected static string $resource = DocsResource::class;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $images = $data['image'] ?? [];

        if (!is_array($images)) {
            $images = [$images];
        }

        foreach ($images as $imagePath) {
            Docs::create([
                'category_id' => $data['category_id'],
                'image'       => $imagePath,
            ]);
        }

        return new Docs();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil menambahkan beberapa Docs!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
