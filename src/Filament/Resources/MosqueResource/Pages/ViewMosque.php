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

                Infolists\Components\Section::make('Images')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('images')
                            ->label('Mosque Images')
                            ->schema([
                                Infolists\Components\ImageEntry::make('file_path')
                                    ->label('')
                                    ->disk('local')
                                    ->width(200)
                                    ->height(150)
                                    ->extraAttributes(['class' => 'rounded-lg shadow-md'])
                                    ->url(fn ($record) => asset('storage/'.str_replace('public/', '', $record->file_path))),
                                Infolists\Components\TextEntry::make('original_url')
                                    ->label('Original URL')
                                    ->limit(50)
                                    ->tooltip(fn ($record) => $record->original_url),
                                Infolists\Components\TextEntry::make('file_size')
                                    ->label('Size')
                                    ->formatStateUsing(fn ($state) => $state ? $this->formatBytes($state) : 'Unknown'),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->placeholder('No images available. Use "Download Images" action to fetch images from the mosque URL.'),
                    ])
                    ->visible(fn ($record) => $record->images()->exists() || ! empty($record->url)),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('download_images')
                ->label('Download Images')
                ->icon('heroicon-o-photo')
                ->color('success')
                ->visible(fn () => ! empty($this->record->url))
                ->action(function () {
                    $downloadedCount = $this->record->downloadImages();

                    if ($downloadedCount > 0) {
                        \Filament\Notifications\Notification::make()
                            ->title("Successfully downloaded {$downloadedCount} images")
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('No new images found to download')
                            ->warning()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Download Images')
                ->modalDescription('This will download all images from the mosque URL and save them to storage.')
                ->modalSubmitActionLabel('Download'),
        ];
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
