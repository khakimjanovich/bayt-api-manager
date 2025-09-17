<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource;

final class CreateProvince extends CreateRecord
{
    protected static string $resource = ProvinceResource::class;
}
