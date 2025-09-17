<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Khakimjanovich\BaytApiManager\Data\Districts\CreateData;

/**
 * @property int $id
 * @property int $province_id
 * @property string $name
 * @property null|float $latitude
 * @property null|float $longitude
 */
final class District extends Model
{
    use HasFactory;

    protected $table = 'bayt_api_manager_districts';

    protected $fillable = ['id', 'province_id', 'name', 'latitude', 'longitude'];

    final public static function create(CreateData $data): self
    {
        return self::query()->create([
            'id' => $data->id, 'province_id' => $data->province_id, 'name' => $data->name,
            'latitude' => $data->latitude, 'longitude' => $data->longitude,
        ]);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function mosques(): HasMany
    {
        return $this->hasMany(Mosque::class);
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
