<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource;

final class EditProvince extends EditRecord
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
