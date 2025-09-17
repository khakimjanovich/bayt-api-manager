<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Data\Provinces;

use Spatie\LaravelData\Data;

final class CreateData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $time_difference = null,
    ) {}
}
