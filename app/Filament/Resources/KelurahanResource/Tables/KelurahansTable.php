<?php

namespace App\Filament\Resources\KelurahanResource\Tables;

use App\Models\Kelurahan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KelurahansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Kelurahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kode_wilayah')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('kecamatan_display')
                    ->label('Kecamatan')
                    ->state(function (Kelurahan $record): string {
                        return $record->kecamatan()->first()?->nama 
                            ?? (string) $record->getRawOriginal('kecamatan') 
                            ?: '-';
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('kecamatan', fn ($q) => $q->where('nama', 'ilike', "%{$search}%"))
                              ->orWhere('kecamatan', 'ilike', "%{$search}%");
                    })
                    ->sortable(query: function ($query, string $direction) {
                        $query->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
                              ->orderBy('kecamatans.nama', $direction)
                              ->select('kelurahans.*');
                    })
                    ->badge()
                    ->color('primary'),

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

                IconColumn::make('akses_jalan')
                    ->label('Jalan')
                    ->boolean(),

                IconColumn::make('listrik')
                    ->boolean(),

                IconColumn::make('air_bersih')
                    ->label('Air')
                    ->boolean(),

                IconColumn::make('internet')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kecamatan_id')
                    ->relationship('kecamatan', 'nama')
                    ->label('Kecamatan')
                    ->searchable()
                    ->preload(),

                Filter::make('has_geom')
                    ->label('Punya Geometry')
                    ->query(fn ($query) => $query->whereNotNull('geom')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nama');
    }
}
