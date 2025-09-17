<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource;

final class ViewMosque extends ViewRecord
{
    protected static string $resource = MosqueResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Basic Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Mosque Name'),
                        Infolists\Components\TextEntry::make('url')
                            ->label('Website URL')
                            ->url(fn ($record) => $record->url)
                            ->openUrlInNewTab()
                            ->placeholder('No URL available'),
                        Infolists\Components\TextEntry::make('bomdod')
                            ->label('Bomdod Time')
                            ->placeholder('Not specified'),
                        Infolists\Components\TextEntry::make('xufton')
                            ->label('Xufton Time')
                            ->placeholder('Not specified'),
                    ])->columns(2),

                Infolists\Components\Section::make('Location')
                    ->schema([
                        Infolists\Components\TextEntry::make('latitude')
                            ->label('Latitude')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 6) : 'Not specified'),
                        Infolists\Components\TextEntry::make('longitude')
                            ->label('Longitude')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 6) : 'Not specified'),
                        Infolists\Components\TextEntry::make('altitude')
                            ->label('Altitude')
                            ->placeholder('Not specified'),
                    ])->columns(3),

            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
