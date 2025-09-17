<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Data\Mosques;

use Spatie\LaravelData\Data;

final class CreateData extends Data
{
    public function __construct(
        public int $id,
        public int $district_id,
        public int $province_id,
        public string $name,
        public ?string $url,
        public ?string $bomdod,
        public ?string $xufton,
        public bool $has_location,
        public float $latitude,
        public float $longitude,
        public ?string $altitude,
        public ?string $distance,
    ) {}
}
