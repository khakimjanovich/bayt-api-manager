<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Filament\Resources\DistrictResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class MosquesRelationManager extends RelationManager
{
    protected static string $relationship = 'mosques';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bomdod')
                    ->label('Bomdod Time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('xufton')
                    ->label('Xufton Time')
                    ->maxLength(255),
                Forms\Components\Toggle::make('has_location')
                    ->label('Has Location')
                    ->default(false),
                Forms\Components\TextInput::make('latitude')
                    ->numeric()
                    ->step(0.00000001),
                Forms\Components\TextInput::make('longitude')
                    ->numeric()
                    ->step(0.00000001),
                Forms\Components\TextInput::make('altitude')
                    ->maxLength(255),
                Forms\Components\TextInput::make('distance')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('url')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return mb_strlen($state) > 30 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('bomdod')
                    ->label('Bomdod'),
                Tables\Columns\TextColumn::make('xufton')
                    ->label('Xufton'),
                Tables\Columns\IconColumn::make('has_location')
                    ->boolean()
                    ->label('Has Location'),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
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
