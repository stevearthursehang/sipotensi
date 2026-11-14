<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelurahanResource\Pages\CreateKelurahan;
use App\Filament\Resources\KelurahanResource\Pages\EditKelurahan;
use App\Filament\Resources\KelurahanResource\Pages\ListKelurahans;
use App\Filament\Resources\KelurahanResource\Tables\KelurahansTable;
use App\Models\Kelurahan;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master GIS';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Administratif Wilayah')
                    ->description('Pilih Kecamatan terlebih dahulu, lalu masukkan nama Kelurahan atau Desa/Kampung.')
                    ->schema([
                        Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->relationship('kecamatan', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (!$state) return;
                                $kec = \App\Models\Kecamatan::find($state);
                                if ($kec) {
                                    $set('kecamatan', $kec->nama);
                                    if ($kec->latitude && $kec->longitude) {
                                        $set('latitude', $kec->latitude);
                                        $set('longitude', $kec->longitude);
                                    }
                                    if ($kec->kode && empty($get('kode_wilayah'))) {
                                        $set('kode_wilayah', "{$kec->kode}.");
                                    }
                                    if (empty($get('alamat_kantor'))) {
                                        $set('alamat_kantor', "Kantor Kelurahan/Kampung, Kec. {$kec->nama}, Kab. Kepulauan Sangihe");
                                    }
                                }
                            })
                            ->helperText('Pilih salah satu dari 15 Kecamatan di Kabupaten Kepulauan Sangihe'),

                        Hidden::make('kecamatan'),

                        TextInput::make('nama')
                            ->label('Nama Kelurahan / Kampung')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Batunderang, Sawang, Santiago...'),

                        TextInput::make('kode_wilayah')
                            ->label('Kode Wilayah / Kemendagri')
                            ->placeholder('Contoh: 71.03.15.2001')
                            ->maxLength(50)
                            ->nullable(),

                        TextInput::make('luas_wilayah')
                            ->label('Luas Wilayah (Hektar / Ha)')
                            ->numeric()
                            ->suffix('Ha')
                            ->nullable(),

                        View::make('filament.forms.components.map-picker')
                            ->columnSpanFull(),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->helperText('Otomatis terisi dari koordinat pusat kecamatan saat kecamatan dipilih'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->helperText('Otomatis terisi dari koordinat pusat kecamatan saat kecamatan dipilih'),

                        Textarea::make('alamat_kantor')
                            ->label('Alamat Kantor Kelurahan / Kampung')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        TextInput::make('jumlah_penduduk')
                            ->label('Jumlah Penduduk')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->required(),

                        TextInput::make('tenaga_kerja_tersedia')
                            ->label('Tenaga Kerja Tersedia')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->required(),
                    ])->columns(2),

                Section::make('Infrastruktur Dasar')
                    ->description('Centang infrastruktur yang tersedia di kelurahan / kampung ini')
                    ->schema([
                        Checkbox::make('akses_jalan')
                            ->label('Akses Jalan Utama'),

                        Checkbox::make('listrik')
                            ->label('Jaringan Listrik (PLN)'),

                        Checkbox::make('air_bersih')
                            ->label('Jaringan Air Bersih (PDAM / Mata Air)'),

                        Checkbox::make('internet')
                            ->label('Jaringan Internet / Telekomunikasi 4G'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return KelurahansTable::configure($table);
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
            'index' => ListKelurahans::route('/'),
            'create' => CreateKelurahan::route('/create'),
            'edit' => EditKelurahan::route('/{record}/edit'),
        ];
    }
}
