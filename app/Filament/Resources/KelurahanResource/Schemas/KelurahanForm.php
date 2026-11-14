<?php

namespace App\Filament\Resources\KelurahanResource\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelurahanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Administratif')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Kelurahan')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('kecamatan')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('alamat_kantor')
                            ->label('Alamat Kantor')
                            ->maxLength(65535),

                        TextInput::make('jumlah_penduduk')
                            ->label('Jumlah Penduduk')
                            ->numeric()
                            ->integer()
                            ->required(),

                        TextInput::make('tenaga_kerja_tersedia')
                            ->label('Tenaga Kerja Tersedia')
                            ->numeric()
                            ->integer()
                            ->required(),
                    ]),

                Section::make('Infrastruktur Dasar')
                    ->description('Centang infrastruktur yang tersedia di kelurahan ini')
                    ->schema([
                        Checkbox::make('akses_jalan')
                            ->label('Akses Jalan'),

                        Checkbox::make('listrik')
                            ->label('Listrik'),

                        Checkbox::make('air_bersih')
                            ->label('Air Bersih'),

                        Checkbox::make('internet')
                            ->label('Internet'),
                    ]),
            ]);
    }
}
