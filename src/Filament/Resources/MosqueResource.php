<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources;

use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Khakimjanovich\Bayt\Exceptions\BaytException;
use Khakimjanovich\BaytApiManager\Facades\BaytApiManager;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages\CreateMosque;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages\EditMosque;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages\ListMosques;
use Khakimjanovich\BaytApiManager\Filament\Resources\MosqueResource\Pages\ViewMosque;
use Khakimjanovich\BaytApiManager\Models\Mosque;

final class MosqueResource extends Resource
{
    protected static ?string $model = Mosque::class;

    protected static ?string $navigationGroup = 'Bayt Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Mosque Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->maxLength(255),

                Map::make('location')
                    ->label('Location')
                    ->columnSpanFull()
                    ->afterStateHydrated(function (Map $component, $state, $record) {
                        if ($record && $record->latitude && $record->longitude) {
                            $component->state([
                                'lat' => (float) $record->latitude,
                                'lng' => (float) $record->longitude,
                            ]);
                        }
                    })
                    ->afterStateUpdated(function (Map $component, $state, Forms\Set $set) {
                        if (is_array($state) && isset($state['lat'], $state['lng'])) {
                            $set('latitude', (string) $state['lat']);
                            $set('longitude', (string) $state['lng']);
                        }
                    })
                    ->extraAttributes([
                        'style' => 'border-radius: 8px',
                    ])
                    ->liveLocation()
                    ->showMarker()
                    ->markerColor('#3b82f6')
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->tilesUrl('https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png')
                    ->zoom(15)
                    ->detectRetina()
                    ->showMyLocationButton()
                    ->clickable(true),

                Forms\Components\Hidden::make('latitude'),
                Forms\Components\Hidden::make('longitude'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Mosque Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->formatStateUsing(function ($record) {
                        if ($record->latitude && $record->longitude) {
                            return number_format((float) $record->latitude, 4).', '.number_format((float) $record->longitude, 4);
                        }

                        return 'No location';
                    })
                    ->color(fn ($record) => $record->latitude && $record->longitude ? 'success' : 'gray')
                    ->icon(fn ($record) => $record->latitude && $record->longitude ? 'heroicon-o-map-pin' : 'heroicon-o-exclamation-triangle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync-bayt-api-mosques')
                    ->label('Sync BAYT mosques')
                    ->icon('heroicon-s-arrow-path-rounded-square')
                    ->action(function () {
                        try {
                            $newly_created = BaytApiManager::syncMosques();
                            Notification::make()
                                ->title("We have successfully synced Bayt mosques, newly created mosques count: $newly_created")
                                ->success()
                                ->send();
                        } catch (BaytException $e) {
                            Notification::make()
                                ->title($e->getMessage())
                                ->danger()
                                ->body($e->getPrevious())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMosques::route('/'),
            'create' => CreateMosque::route('/create'),
            'view' => ViewMosque::route('/{record}'),
            'edit' => EditMosque::route('/{record}/edit'),
        ];
    }
}
