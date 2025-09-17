<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\ProvinceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class DistrictsRelationManager extends RelationManager
{
    protected static string $relationship = 'districts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('latitude')
                    ->numeric()
                    ->step(0.00000001),
                Forms\Components\TextInput::make('longitude')
                    ->numeric()
                    ->step(0.00000001),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
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
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
