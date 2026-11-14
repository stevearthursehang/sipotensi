<?php

namespace App\Filament\Widgets;

use App\Models\Kelurahan;
use App\Models\MinatInvestor;
use App\Models\PotensiInvestasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PotensiStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPotensi = PotensiInvestasi::count();
        $totalNilai = PotensiInvestasi::sum('nilai_estimasi_investasi');
        $nilaiMiliar = $totalNilai > 0 ? round($totalNilai / 1000000000, 2) : 0;

        // Potensi Sektor Wisata / Pariwisata
        $wisataCount = PotensiInvestasi::where(function ($q) {
            $q->where('jenis_potensi', 'ilike', '%pariwisata%')
              ->orWhere('jenis_potensi', 'ilike', '%wisata%')
              ->orWhere('komoditas_unggulan', 'ilike', '%wisata%')
              ->orWhere('nama', 'ilike', '%wisata%');
        })->count();

        $wisataNilai = PotensiInvestasi::where(function ($q) {
            $q->where('jenis_potensi', 'ilike', '%pariwisata%')
              ->orWhere('jenis_potensi', 'ilike', '%wisata%')
              ->orWhere('komoditas_unggulan', 'ilike', '%wisata%')
              ->orWhere('nama', 'ilike', '%wisata%');
        })->sum('nilai_estimasi_investasi');

        $wisataMiliar = $wisataNilai > 0 ? round($wisataNilai / 1000000000, 2) : 0;

        // Sektor Perikanan
        $perikananCount = PotensiInvestasi::where(function ($q) {
            $q->where('jenis_potensi', 'ilike', '%perikanan%')
              ->orWhere('komoditas_unggulan', 'ilike', '%ikan%')
              ->orWhere('komoditas_unggulan', 'ilike', '%tuna%');
        })->count();

        $totalKelurahan = Kelurahan::count();
        $totalMinat = MinatInvestor::count();

        return [
            Stat::make('Total Peluang Investasi', "{$totalPotensi} Titik")
                ->description('Tersebar di wilayah Kepulauan Sangihe')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Estimasi Nilai Total', "Rp {$nilaiMiliar} Miliar")
                ->description('Total kalkulasi nilai investasi proyek')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Potensi Sektor Wisata', "{$wisataCount} Destinasi")
                ->description("Nilai Rp {$wisataMiliar} Miliar (Bahari, Budaya & Alam)")
                ->descriptionIcon('heroicon-m-sun')
                ->color('warning'),

            Stat::make('Potensi Perikanan & Kelautan', "{$perikananCount} Lokasi")
                ->description('Komoditas unggulan maritim & perikanan')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),

            Stat::make('Wilayah Terdata (GIS)', "{$totalKelurahan} Kelurahan/Kampung")
                ->description('15 Kecamatan Kab. Kepulauan Sangihe')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('gray'),

            Stat::make('Minat Investor Masuk', "{$totalMinat} Formulir")
                ->description('Pernyataan minat masuk dari portal')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($totalMinat > 0 ? 'success' : 'gray'),
        ];
    }
}

