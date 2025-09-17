<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

final class BaytApiManagerPlugin implements Plugin
{
    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'bayt-api-manager-plugin';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                Filament\Resources\MosqueResource::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Bayt Manager')
                    ->icon('heroicon-s-home-modern'),
            ]);
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
