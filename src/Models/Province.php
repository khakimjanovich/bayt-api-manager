<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Khakimjanovich\BaytApiManager\Data\Provinces\CreateData;

/**
 * @property int $id
 */
final class Province extends Model
{
    use HasFactory;

    protected $table = 'bayt_api_manager_provinces';

    protected $fillable = [
        'id', 'name', 'latitude', 'longitude', 'time_difference',
    ];

    public static function create(CreateData $param)
    {
        return self::query()->create([
            'id' => $param->id, 'name' => $param->name, 'latitude' => $param->latitude,
            'longitude' => $param->longitude, 'time_difference' => $param->time_difference,
        ]);
    }

    public function mosques(): HasMany
    {
        return $this->hasMany(Mosque::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
