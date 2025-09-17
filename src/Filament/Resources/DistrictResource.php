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
use Khakimjanovich\BaytApiManager\Models\District;

final class DistrictResource extends Resource
{
    protected static ?string $model = District::class;

    protected static ?string $navigationGroup = 'Bayt Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required(),
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
                Tables\Columns\TextColumn::make('province.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\Action::make('sync-bayt-api-districts')
                    ->label('Sync BAYT districts')
                    ->icon('heroicon-s-arrow-path-rounded-square')
                    ->action(function () {
                        try {
                            $newly_created = BaytApiManager::syncDistricts();
                            Notification::make()
                                ->title("We have successfully synced Bayt districts, newly created districts count: $newly_created")
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
            'index' => DistrictResource\Pages\ListDistricts::route('/'),
            'create' => DistrictResource\Pages\CreateDistrict::route('/create'),
            'edit' => DistrictResource\Pages\EditDistrict::route('/{record}/edit'),
        ];
    }
}
