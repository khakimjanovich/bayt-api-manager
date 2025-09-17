<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $mosque_id
 * @property string $original_url
 * @property string $file_path
 * @property string $file_name
 * @property int|null $file_size
 * @property string|null $mime_type
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Mosque $mosque
 */
final class MosqueImage extends Model
{
    protected $table = 'bayt_api_manager_mosque_images';

    protected $fillable = [
        'mosque_id',
        'original_url',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'file_size' => 'integer',
    ];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    /**
     * Get the full storage path for the image.
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/'.$this->file_path);
    }

    /**
     * Get the public URL for the image.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/'.str_replace('public/', '', $this->file_path));
    }
}
