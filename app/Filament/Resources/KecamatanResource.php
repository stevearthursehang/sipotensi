<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages;
use App\Filament\Resources\KecamatanResource\RelationManagers\KelurahansRelationManager;
use App\Models\Kecamatan;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master GIS';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kecamatan';

    protected static ?string $modelLabel = 'Kecamatan';

    protected static ?string $pluralModelLabel = 'Kecamatan';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kecamatan')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kecamatan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Wilayah / Kemendagri')
                            ->placeholder('Contoh: 71.03.15')
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\TextInput::make('luas_total')
                            ->label('Luas Wilayah Total (Ha)')
                            ->numeric()
                            ->suffix('Ha'),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi / Profil Singkat')
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Koordinat Pusat (Centroid)')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelurahans_count')
                    ->label('Jumlah Kelurahan/Kampung')
                    ->counts('kelurahans')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('luas_total')
                    ->label('Luas Total (Ha)')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' Ha')
                    ->sortable(),

                Tables\Columns\TextColumn::make('latitude')
                    ->label('Latitude')
                    ->numeric(decimalPlaces: 6)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longitude')
                    ->numeric(decimalPlaces: 6)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (Kecamatan $record, DeleteAction $action) {
                        if ($record->kelurahans()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Kecamatan Tidak Dapat Dihapus')
                                ->body("Kecamatan {$record->nama} masih memiliki {$record->kelurahans()->count()} kelurahan/kampung terkait. Hapus atau pindahkan kelurahan terlebih dahulu.")
                                ->send();
                            $action->halt();
                        }
                    }),
                Action::make('recalculate')
                    ->label('Hitung Koordinat')
                    ->tooltip('Hitung ulang titik centroid & luas total dari kelurahan terkait')
                    ->icon('heroicon-m-calculator')
                    ->color('success')
                    ->action(function (Kecamatan $record) {
                        $record->recalculateCentroidAndArea();
                        Notification::make()
                            ->title('Koordinat & Luas Diperbarui')
                            ->body("Titik pusat {$record->nama} dan total luas berhasil dihitung ulang dari kelurahan terkait.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, \Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                if ($record->kelurahans()->count() > 0) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Penghapusan Dibatalkan')
                                        ->body("Kecamatan {$record->nama} masih memiliki {$record->kelurahans()->count()} kelurahan terkait.")
                                        ->send();
                                    $action->halt();
                                    return;
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('nama', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            KelurahansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
        ];
    }
}

