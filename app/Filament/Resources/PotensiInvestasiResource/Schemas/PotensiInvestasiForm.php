<?php

namespace App\Filament\Resources\PotensiInvestasiResource\Schemas;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;

class PotensiInvestasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Wilayah & Lokasi Administratif')
                    ->description('Pilih Kecamatan dan Kelurahan/Kampung. Koordinat latitude & longitude serta data wilayah akan otomatis terisi.')
                    ->schema([
                        Select::make('filter_kecamatan')
                            ->label('Kecamatan')
                            ->options(Kecamatan::pluck('nama', 'id'))
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
                                    $kec = Kecamatan::find($state);
                                    if ($kec && $kec->latitude && $kec->longitude) {
                                        $set('latitude', $kec->latitude);
                                        $set('longitude', $kec->longitude);
                                        $set('alamat', "Kecamatan {$kec->nama}, Kab. Kepulauan Sangihe");
                                    }
                                }
                            })
                            ->helperText('Pilih kecamatan untuk auto-fill titik pusat koordinat & menyaring kelurahan'),

                        Select::make('kelurahan_id')
                            ->label('Kelurahan / Kampung')
                            ->options(function ($get) {
                                $kecId = $get('filter_kecamatan');
                                if ($kecId) {
                                    return Kelurahan::where('kecamatan_id', $kecId)->pluck('nama', 'id');
                                }
                                return Kelurahan::pluck('nama', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                if (!$state) return;
                                $kel = Kelurahan::find($state);
                                if ($kel) {
                                    // Auto-fill coordinates and details
                                    if ($kel->latitude) $set('latitude', $kel->latitude);
                                    if ($kel->longitude) $set('longitude', $kel->longitude);
                                    if ($kel->alamat_kantor) $set('alamat', "Kelurahan/Kampung {$kel->nama}, {$kel->alamat_kantor}");
                                    $set('akses_jalan', (bool) $kel->akses_jalan);
                                    $set('listrik', (bool) $kel->listrik);
                                    $set('air_bersih', (bool) $kel->air_bersih);
                                    $set('internet', (bool) $kel->internet);

                                    // If kecamatan was not selected, auto-select it
                                    if ($kel->kecamatan_id) {
                                        $set('filter_kecamatan', $kel->kecamatan_id);
                                    }
                                }
                            })
                            ->helperText('Memilih kelurahan akan otomatis mengisi koordinat Latitude & Longitude'),
                    ])->columns(2),

                Section::make('Informasi Utama Potensi Investasi')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Potensi Investasi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('jenis_potensi')
                            ->label('Jenis / Sektor Potensi')
                            ->placeholder('Contoh: Pariwisata, Perikanan, Pertanian')
                            ->maxLength(255),

                        TextInput::make('komoditas_unggulan')
                            ->label('Komoditas Unggulan')
                            ->maxLength(255),

                        Select::make('skala_peluang')
                            ->label('Skala Peluang')
                            ->options([
                                'kecil' => 'Kecil',
                                'menengah' => 'Menengah',
                                'besar' => 'Besar',
                            ])
                            ->required(),

                        TextInput::make('nilai_estimasi_investasi')
                            ->label('Nilai Estimasi Investasi')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('roi_estimasi')
                            ->label('Estimasi ROI')
                            ->numeric()
                            ->suffix('%'),

                        Select::make('status_lahan')
                            ->label('Status Lahan')
                            ->options([
                                'tersedia' => 'Tersedia',
                                'dalam_proses' => 'Dalam Proses',
                                'tidak_tersedia' => 'Tidak Tersedia',
                            ])
                            ->required(),

                        Select::make('status_perizinan')
                            ->label('Status Perizinan')
                            ->options([
                                'sudah_izin' => 'Sudah Izin',
                                'dalam_proses' => 'Dalam Proses',
                                'belum_izin' => 'Belum Izin',
                            ])
                            ->required(),

                        Select::make('status_kesiapan_lahan')
                            ->label('Kesiapan Lahan')
                            ->options([
                                'siap' => 'Siap',
                                'dalam_persiapan' => 'Dalam Persiapan',
                                'belum_siap' => 'Belum Siap',
                            ]),
                    ])
                    ->columns(2),

                Section::make('Lokasi & Aksesibilitas')
                    ->schema([
                        View::make('filament.forms.components.map-picker')
                            ->columnSpanFull(),

                        Textarea::make('alamat')
                            ->label('Alamat Lokasi')
                            ->columnSpanFull(),

                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->helperText('Otomatis terisi saat pin di peta digeser atau kelurahan dipilih'),

                        TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->helperText('Otomatis terisi saat pin di peta digeser atau kelurahan dipilih'),

                        TextInput::make('jarak_pelabuhan')
                            ->label('Jarak ke Pelabuhan')
                            ->numeric()
                            ->suffix('km'),

                        TextInput::make('jarak_bandara')
                            ->label('Jarak ke Bandara')
                            ->numeric()
                            ->suffix('km'),
                    ])
                    ->columns(2),

                Section::make('Ketersediaan Infrastruktur Pendukung')
                    ->schema([
                        Checkbox::make('akses_jalan')
                            ->label('Akses Jalan Memadai'),

                        Checkbox::make('listrik')
                            ->label('Jaringan Listrik'),

                        Checkbox::make('air_bersih')
                            ->label('Sumber Air Bersih'),

                        Checkbox::make('internet')
                            ->label('Jaringan Internet / Telekomunikasi'),
                    ])
                    ->columns(4),

                Section::make('Deskripsi & Dokumen Pendukung')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Lengkap')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('foto_utama')
                            ->label('Foto Utama')
                            ->image()
                            ->disk('public')
                            ->directory('potensi-investasi/foto-utama')
                            ->visibility('public'),

                        FileUpload::make('galeri_foto')
                            ->label('Galeri Foto')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('potensi-investasi/galeri')
                            ->visibility('public'),

                        FileUpload::make('pdfs')
                            ->label('Dokumen Prospektus / PDF')
                            ->disk('public')
                            ->multiple()
                            ->extraInputAttributes(['accept' => '.pdf,application/pdf'])
                            ->directory('potensi-investasi/pdf')
                            ->visibility('public'),
                    ])
                    ->columns(2),
            ]);
    }
}
