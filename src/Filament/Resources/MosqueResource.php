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
                Forms\Components\Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('district_id', null)),

                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name', fn (\Illuminate\Database\Eloquent\Builder $query, callable $get) =>
                        $query->when($get('province_id'), fn ($q, $provinceId) => $q->where('province_id', $provinceId))
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('name')
                    ->label('Mosque Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('province.name')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('District')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Mosque Name')
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
