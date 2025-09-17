<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Data\Provinces;

use Spatie\LaravelData\Data;

final class CreateData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $latitude,
        public string $longitude,
        public int $time_difference,
    ) {}
}
