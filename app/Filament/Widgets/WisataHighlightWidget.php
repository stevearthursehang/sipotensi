<?php

namespace App\Filament\Widgets;

use App\Models\PotensiInvestasi;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class WisataHighlightWidget extends BaseWidget
{
    protected static ?string $heading = 'Peluang Investasi Sektor Pariwisata & Wisata Unggulan Sangihe';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PotensiInvestasi::query()
                    ->where(function ($q) {
                        $q->where('jenis_potensi', 'ilike', '%pariwisata%')
                          ->orWhere('jenis_potensi', 'ilike', '%wisata%')
                          ->orWhere('komoditas_unggulan', 'ilike', '%wisata%')
                          ->orWhere('nama', 'ilike', '%wisata%');
                    })
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Destinasi / Peluang')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelurahan.nama')
                    ->label('Kelurahan/Kampung')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('kelurahan.kecamatan')
                    ->label('Kecamatan'),

                Tables\Columns\TextColumn::make('komoditas_unggulan')
                    ->label('Fokus Daya Tarik')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('nilai_estimasi_investasi')
                    ->label('Estimasi Nilai')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('skala_peluang')
                    ->label('Skala')
                    ->badge(),

                Tables\Columns\TextColumn::make('status_lahan')
                    ->label('Kesiapan Lahan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dalam_proses' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Detail')
                    ->icon('heroicon-m-eye')
                    ->url(fn (PotensiInvestasi $record): string => route('filament.admin.resources.potensi-investasis.view', $record)),
            ]);
    }
}

