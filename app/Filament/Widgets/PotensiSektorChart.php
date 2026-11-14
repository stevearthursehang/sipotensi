<?php

namespace App\Filament\Widgets;

use App\Models\PotensiInvestasi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PotensiSektorChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Peluang Investasi per Sektor';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = PotensiInvestasi::select('jenis_potensi', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_potensi')
            ->groupBy('jenis_potensi')
            ->orderByDesc('total')
            ->get();

        $labels = $data->pluck('jenis_potensi')->toArray();
        $counts = $data->pluck('total')->toArray();

        $colors = [
            '#f59e0b', // Pariwisata / Kuning
            '#3b82f6', // Perikanan / Biru
            '#10b981', // Pertanian / Hijau
            '#8b5cf6', // Peternakan / Ungu
            '#64748b', // Industri / Abu
            '#f97316', // Energi / Oranye
            '#ec4899', // Lainnya / Pink
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Titik Potensi',
                    'data' => $counts,
                    'backgroundColor' => array_slice($colors, 0, count($counts)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
