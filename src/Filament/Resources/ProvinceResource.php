<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Khakimjanovich\Bayt\Exceptions\BaytException;
use Khakimjanovich\BaytApiManager\Facades\BaytApiManager;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages\CreateProvince;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages\EditProvince;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\Pages\ListProvinces;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\RelationManagers\DistrictsRelationManager;
use Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\RelationManagers\MosquesRelationManager;
use Khakimjanovich\BaytApiManager\Models\Province;

final class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationGroup = 'Bayt Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('districts_count')
                    ->counts('districts')
                    ->label('Districts'),
                Tables\Columns\TextColumn::make('mosques_count')
                    ->counts('mosques')
                    ->label('Mosques'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Coordinates')
                    ->formatStateUsing(function ($record) {
                        if ($record->latitude && $record->longitude) {
                            return number_format((float) $record->latitude, 4).', '.number_format((float) $record->longitude, 4);
                        }

                        return 'No location';
                    })
                    ->color(fn ($record) => $record->latitude && $record->longitude ? 'success' : 'gray')
                    ->icon(fn ($record) => $record->latitude && $record->longitude ? 'heroicon-o-map-pin' : 'heroicon-o-exclamation-triangle')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync-bayt-api-provinces')
                    ->label('Sync BAYT provinces')
                    ->icon('heroicon-s-arrow-path-rounded-square')
                    ->action(function () {
                        try {
                            $newly_created = BaytApiManager::syncProvinces();
                            Notification::make()
                                ->title("We have successfully synced Bayt provinces, newly created provinces count: $newly_created")
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
            DistrictsRelationManager::class,
            MosquesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProvinces::route('/'),
            'create' => CreateProvince::route('/create'),
            'edit' => EditProvince::route('/{record}/edit'),
        ];
    }
}
