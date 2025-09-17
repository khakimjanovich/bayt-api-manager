<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager;

use Illuminate\Database\Eloquent\Collection;
use Khakimjanovich\Bayt\Exceptions\BaytException;
use Khakimjanovich\Bayt\Facades\Bayt;
use Khakimjanovich\BaytApiManager\Data\Mosques\CreateData;
use Khakimjanovich\BaytApiManager\Models\District;
use Khakimjanovich\BaytApiManager\Models\Mosque;
use Khakimjanovich\BaytApiManager\Models\Province;

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

    /**
     * @throws BaytException
     */
    public function syncProvinces(): int
    {
        $provinces = Bayt::provinces();

        $newly_created_provinces = 0;
        foreach ($provinces as $province) {
            $province_exists = Province::query()->where('id', $province->id)->exists();

            if (! $province_exists) {
                $newly_created_provinces++;
                Province::create(new Data\Provinces\CreateData(
                    $province->id, $province->name, $province->latitude, $province->longitude, $province->time_difference,
                ));
            }
        }

        return $newly_created_provinces;
    }

    /**
     * @throws BaytException
     */
    public function syncDistricts(): int
    {
        /** @var Collection<Province> $provinces */
        $provinces = Province::query()->select('id')->get();
        $newly_created_districts = 0;
        foreach ($provinces as $province) {
            $districts = Bayt::districtsByProvince($province->id);
            foreach ($districts as $district) {
                $district_exists = District::query()->where('id', $district->id)->exists();
                if (! $district_exists) {
                    $newly_created_districts++;
                    District::create(new Data\Districts\CreateData(
                        $district->id, $province->id, $district->name, $district->latitude, $district->longitude
                    ));
                }
            }
        }

        return $newly_created_districts;
    }
}
