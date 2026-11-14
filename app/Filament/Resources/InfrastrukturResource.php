<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfrastrukturResource\Pages;
use App\Models\Infrastruktur;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class InfrastrukturResource extends Resource
{
    protected static ?string $model = Infrastruktur::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master GIS';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Infrastruktur';

    protected static ?string $modelLabel = 'Infrastruktur';

    protected static ?string $pluralModelLabel = 'Infrastruktur';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Wilayah & Lokasi Administratif')
                    ->description('Pilih Kecamatan dan Kelurahan/Kampung lokasi infrastruktur. Koordinat peta akan otomatis terisi.')
                    ->schema([
                        Forms\Components\Select::make('filter_kecamatan')
                            ->label('Kecamatan')
                            ->options(\App\Models\Kecamatan::pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record && $record->kelurahan) {
                                    $set('filter_kecamatan', $record->kelurahan->kecamatan_id);
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                $set('kelurahan_id', null);
                                if ($state) {
                                    $kec = \App\Models\Kecamatan::find($state);
                                    if ($kec && $kec->latitude && $kec->longitude) {
                                        $set('latitude', $kec->latitude);
                                        $set('longitude', $kec->longitude);
                                    }
                                }
                            })
                            ->helperText('Pilih kecamatan untuk auto-fill titik pusat koordinat & menyaring kelurahan'),

                        Forms\Components\Select::make('kelurahan_id')
                            ->label('Kelurahan / Kampung')
                            ->options(function ($get) {
                                $kecId = $get('filter_kecamatan');
                                if ($kecId) {
                                    return \App\Models\Kelurahan::where('kecamatan_id', $kecId)->pluck('nama', 'id');
                                }
                                return \App\Models\Kelurahan::pluck('nama', 'id');
                            })
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                if (!$state) return;
                                $kel = \App\Models\Kelurahan::find($state);
                                if ($kel) {
                                    if ($kel->latitude) $set('latitude', $kel->latitude);
                                    if ($kel->longitude) $set('longitude', $kel->longitude);
                                    if ($kel->kecamatan_id) $set('filter_kecamatan', $kel->kecamatan_id);
                                }
                            })
                            ->helperText('Opsional. Memilih kelurahan otomatis mengisi koordinat Latitude & Longitude'),
                    ])->columns(2),

                Section::make('Informasi Utama Infrastruktur')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Infrastruktur')
                            ->required()
                            ->placeholder('Contoh: Taman Laut Pelabuhan Tua, Pelabuhan Nusantara Tahuna...')
                            ->maxLength(255),

                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Infrastruktur')
                            ->options([
                                'pelabuhan' => 'Pelabuhan',
                                'bandara' => 'Bandara',
                                'jalan' => 'Jalan',
                                'listrik' => 'Listrik',
                                'air_bersih' => 'Air Bersih',
                                'internet' => 'Internet',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi / Catatan Lapangan')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Koordinat Spasial & Titik Lokasi')
                    ->schema([
                        View::make('filament.forms.components.map-picker')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->required(),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelurahan.nama')
                    ->label('Kelurahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelurahan.kecamatan.nama')
                    ->label('Kecamatan')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('latitude'),

                Tables\Columns\TextColumn::make('longitude'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->relationship('kelurahan.kecamatan', 'nama')
                    ->label('Kecamatan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('kelurahan_id')
                    ->relationship('kelurahan', 'nama')
                    ->label('Kelurahan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'jalan' => 'Jalan',
                        'pelabuhan' => 'Pelabuhan',
                        'bandara' => 'Bandara',
                        'listrik' => 'Listrik',
                        'air_bersih' => 'Air Bersih',
                        'internet' => 'Internet',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInfrastrukturs::route('/'),
            'create' => Pages\CreateInfrastruktur::route('/create'),
            'edit' => Pages\EditInfrastruktur::route('/{record}/edit'),
        ];
    }
}
