<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource;

final class EditMosque extends EditRecord
{
    protected static string $resource = MosqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
