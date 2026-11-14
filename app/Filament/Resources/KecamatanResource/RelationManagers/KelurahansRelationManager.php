<?php

namespace App\Filament\Resources\KecamatanResource\RelationManagers;

use App\Models\Kelurahan;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelurahansRelationManager extends RelationManager
{
    protected static string $relationship = 'kelurahans';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $title = 'Daftar Kelurahan / Kampung';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelurahan / Kampung')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Kelurahan / Kampung')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('kode_wilayah')
                            ->label('Kode Wilayah / Kemendagri')
                            ->placeholder('Contoh: 71.03.15.2001')
                            ->maxLength(50)
                            ->nullable(),

                        TextInput::make('luas_wilayah')
                            ->label('Luas Wilayah (Ha)')
                            ->numeric()
                            ->suffix('Ha'),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->default(fn () => $this->getOwnerRecord()->latitude)
                            ->helperText('Otomatis mengambil koordinat pusat kecamatan ini jika dikosongkan'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->default(fn () => $this->getOwnerRecord()->longitude)
                            ->helperText('Otomatis mengambil koordinat pusat kecamatan ini jika dikosongkan'),

                        TextInput::make('jumlah_penduduk')
                            ->label('Jumlah Penduduk')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('tenaga_kerja_tersedia')
                            ->label('Tenaga Kerja Tersedia')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Textarea::make('alamat_kantor')
                            ->label('Alamat Kantor')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Infrastruktur')
                    ->schema([
                        Checkbox::make('akses_jalan')->label('Akses Jalan Utama'),
                        Checkbox::make('listrik')->label('Jaringan Listrik (PLN)'),
                        Checkbox::make('air_bersih')->label('Air Bersih (PDAM/Mata Air)'),
                        Checkbox::make('internet')->label('Jaringan Internet 4G'),
                    ])->columns(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Desa / Kelurahan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kode_wilayah')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jumlah_penduduk')
                    ->label('Penduduk')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tenaga_kerja_tersedia')
                    ->label('Tenaga Kerja')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('luas_wilayah')
                    ->label('Luas (Ha)')
                    ->numeric(2)
                    ->suffix(' Ha')
                    ->sortable(),

                TextColumn::make('latitude')
                    ->label('Latitude')
                    ->numeric(6),

                TextColumn::make('longitude')
                    ->label('Longitude')
                    ->numeric(6),

                IconColumn::make('akses_jalan')
                    ->label('Jalan')
                    ->boolean(),

                IconColumn::make('listrik')
                    ->label('Listrik')
                    ->boolean(),

                IconColumn::make('air_bersih')
                    ->label('Air')
                    ->boolean(),

                IconColumn::make('internet')
                    ->label('4G')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Kelurahan / Kampung')
                    ->mutateFormDataUsing(function (array $data): array {
                        $owner = $this->getOwnerRecord();
                        $data['kecamatan_id'] = $owner->id;
                        $data['kecamatan'] = $owner->nama;
                        if (empty($data['latitude'])) $data['latitude'] = $owner->latitude;
                        if (empty($data['longitude'])) $data['longitude'] = $owner->longitude;
                        return $data;
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

