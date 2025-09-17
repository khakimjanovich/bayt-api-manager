<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource;

final class ListMosques extends ListRecords
{
    protected static string $resource = MosqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
