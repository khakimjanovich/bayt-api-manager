<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource;

final class ListProvinces extends ListRecords
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
