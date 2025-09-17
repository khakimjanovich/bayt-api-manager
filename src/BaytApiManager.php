<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager;

use Khakimjanovich\Bayt\Exceptions\BaytException;
use Khakimjanovich\Bayt\Facades\Bayt;
use Khakimjanovich\BaytApiManager\Data\Mosques\CreateData;
use Khakimjanovich\BaytApiManager\Models\Mosque;

final class BaytApiManager
{
    /**
     * @throws BaytException
     */
    public function syncMosques(): int
    {
        $mosques = Bayt::mosques();

        $newly_created_mosques = 0;
        foreach ($mosques as $mosque) {
            $mosque_exists = Mosque::query()->where('id', $mosque->id)->exists();
            if (! $mosque_exists) {
                $newly_created_mosques++;
                Mosque::create(new CreateData(
                    $mosque->id, $mosque->district_id, $mosque->province_id, $mosque->name, $mosque->url,
                    $mosque->bomdod, $mosque->xufton, $mosque->has_location, $mosque->latitude,
                    $mosque->longitude, $mosque->altitude, $mosque->distance,
                ));
            }
        }

        return $newly_created_mosques;
    }
}
