<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\DistrictResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\DistrictResource;

final class CreateDistrict extends CreateRecord
{
    protected static string $resource = DistrictResource::class;
}
