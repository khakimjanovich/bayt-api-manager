<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Khakimjanovich\BaytApiManager\Data\Mosques\CreateData;

/**
 * @property int $id
 * @property int $district_id
 * @property int $province_id
 * @property string $name
 * @property string|null $url
 * @property string|null $image
 * @property string|null $bomdod
 * @property string|null $xufton
 * @property bool $has_location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $altitude
 * @property string|null distance
 */
final class Mosque extends Model
{
    use HasFactory;

    protected $table = 'bayt_api_manager_mosques';

    protected $fillable = [
        'id', 'district_id', 'province_id', 'name', 'url', 'bomdod', 'xufton', 'has_location',
        'latitude', 'longitude', 'altitude', 'distance',
    ];

    final public static function create(CreateData $data): self
    {
        return self::query()->create([
            'id' => $data->id, 'district_id' => $data->district_id, 'province_id' => $data->province_id,
            'name' => $data->name, 'url' => $data->url, 'bomdod' => $data->bomdod, 'xufton' => $data->xufton,
            'has_location' => $data->has_location, 'latitude' => $data->latitude, 'longitude' => $data->longitude,
            'altitude' => $data->altitude, 'distance' => $data->distance,
        ]);
    }

    public function scopeNearest(Builder $query, float $latitude, float $longitude): Builder
    {
        return $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw('
                *,
                (
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance_km
            ', [$latitude, $longitude, $latitude])
            ->orderBy('distance_km');
    }

    public function scopeWithinRadius(Builder $query, float $latitude, float $longitude, float $radiusKm): Builder
    {
        return $query
            ->nearest($latitude, $longitude)
            ->havingRaw('distance_km <= ?', [$radiusKm]);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ],
            set: fn (array $value) => [
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude'],
            ],
        );
    }
}
