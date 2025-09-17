<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int syncMosques
 *
 * @see \Khakimjanovich\BaytApiManager\BaytApiManager
 */
final class BaytApiManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bayt-api-manager';
    }
}
