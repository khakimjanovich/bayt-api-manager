<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Data\Districts;

use Spatie\LaravelData\Data;

final class CreateData extends Data
{
    public function __construct(
        public int $id,
        public int $province_id,
        public string $name,
        public float $latitude,
        public float $longitude,
    ) {}
}
