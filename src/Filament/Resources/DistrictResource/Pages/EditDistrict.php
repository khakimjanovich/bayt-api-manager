<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\DistrictResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\DistrictResource;

final class EditDistrict extends EditRecord
{
    protected static string $resource = DistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
