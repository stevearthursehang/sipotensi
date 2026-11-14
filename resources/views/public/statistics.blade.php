@extends('layouts.app')

@section('title', 'Statistik & Infografis Investasi - GIS Potensi Investasi Sangihe')

@push('styles')
<style>
    .stat-container {
        max-width: 1300px;
        margin: 0 auto;
    }
    .stat-kpi-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }
    .stat-kpi-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-chart-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        height: 100%;
    }
    .stat-chart-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .progress-bar-stat {
        height: 10px;
        border-radius: 6px;
        background: #f1f5f9;
        overflow: hidden;
        margin-top: 6px;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 1s ease-in-out;
    }
    .advantage-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        transition: transform 0.2s;
    }
    .advantage-box:hover {
        transform: translateY(-2px);
    }
    @media print {
        .navbar-gis, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 stat-container">
    <!-- Breadcrumb & Back -->
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ url('/map') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Peta Interaktif
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/map') }}">GIS Investasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Statistik & Infografis</li>
            </ol>
        </nav>
    </div>

    <!-- Header Hero -->
    <div class="p-4 rounded-4 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">
                        <i class="bi bi-bar-chart-fill me-1"></i> Dashboard Eksekutif
                    </span>
                    <span class="badge bg-info text-dark rounded-pill px-3 py-1 fw-semibold">
                        Data Terbuka Pemkab Sangihe
                    </span>
                </div>
                <h1 class="h3 fw-bold mb-1">Statistik & Infografis Investasi Daerah</h1>
                <p class="text-white-50 small mb-0">
                    Potret makro sebaran sektor, kesiapan lahan, utilitas infrastruktur, dan kemudahan berusaha di Kabupaten Kepulauan Sangihe.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 no-print">
                <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-printer-fill text-warning"></i>
                    <span>Cetak Dashboard</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 Key Indicator Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Peluang -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Total Peluang Investasi</span>
                        <h2 class="fw-extrabold text-dark mt-1 mb-0" id="statPotensiPublik">{{ $totalPotensi }}</h2>
                        <small class="text-muted" style="font-size: 0.72rem;">Terpetakan di 15 Kecamatan</small>
                    </div>
                    <div class="stat-kpi-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-compass-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Akumulasi Nilai CAPEX -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Nilai Total Estimasi</span>
                        <h2 class="fw-extrabold text-success mt-1 mb-0" id="statNilaiPublik">
                            @if($totalNilai >= 1000000000)
                                Rp {{ number_format($totalNilai / 1000000000, 1, ',', '.') }} M
                            @else
                                Rp {{ number_format($totalNilai / 1000000, 1, ',', '.') }} Jt
                            @endif
                        </h2>
                        <small class="text-muted" style="font-size: 0.72rem;">Akumulasi nilai modal peluang</small>
                    </div>
                    <div class="stat-kpi-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelurahan & Desa -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Kelurahan / Desa</span>
                        <h2 class="fw-extrabold text-info mt-1 mb-0" id="statKelurahanPublik">{{ $totalKelurahan }}</h2>
                        <small class="text-muted" style="font-size: 0.72rem;">Cakupan batas wilayah spasial</small>
                    </div>
                    <div class="stat-kpi-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minat e-LoI -->
        <div class="col-sm-6 col-lg-3">
            <div class="stat-kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold">Minat Investasi (e-LoI)</span>
                        <h2 class="fw-extrabold text-warning mt-1 mb-0" id="statMinatPublik">{{ $totalMinat }}</h2>
                        <small class="text-muted" style="font-size: 0.72rem;">Pernyataan minat terfasilitasi</small>
                    </div>
                    <div class="stat-kpi-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-envelope-paper-heart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Sektor & Kecamatan -->
    <div class="row g-4 mb-4">
        <!-- Distribusi Sektor (Donut Chart) -->
        <div class="col-lg-6">
            <div class="stat-chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="stat-chart-title">
                            <i class="bi bi-pie-chart-fill text-primary"></i> Distribusi Nilai Investasi per Sektor
                        </h3>
                        <small class="text-muted">Komposisi nilai estimasi modal (CAPEX) berdasarkan bidang usaha</small>
                    </div>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="sektorChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sebaran per Kecamatan (Horizontal Bar Chart) -->
        <div class="col-lg-6">
            <div class="stat-chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="stat-chart-title">
                            <i class="bi bi-bar-chart-fill text-success"></i> Peluang Investasi per Kecamatan
                        </h3>
                        <small class="text-muted">Kecamatan dengan konsentrasi peluang dan nilai tertinggi</small>
                    </div>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="kecamatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: Infrastruktur Readiness & Status Lahan -->
    <div class="row g-4 mb-4">
        <!-- Kesiapan Infrastruktur & Utilitas -->
        <div class="col-lg-6">
            <div class="stat-chart-card">
                <h3 class="stat-chart-title mb-1">
                    <i class="bi bi-lightning-charge-fill text-warning"></i> Kesiapan Utilitas & Infrastruktur Wilayah
                </h3>
                <small class="text-muted d-block mb-4">Persentase peluang investasi yang telah didukung sarana primer</small>

                <!-- Akses Jalan -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark"><i class="bi bi-truck text-primary me-2"></i> Akses Jalan Logistik (Truk/Kontainer)</span>
                        <span class="small fw-extrabold text-primary">{{ $infraStats['akses_jalan'] }}%</span>
                    </div>
                    <div class="progress-bar-stat">
                        <div class="progress-bar-fill bg-primary" style="width: {{ $infraStats['akses_jalan'] }}%;"></div>
                    </div>
                </div>

                <!-- Pasokan Listrik -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark"><i class="bi bi-lightning-fill text-warning me-2"></i> Jaringan Listrik PLN</span>
                        <span class="small fw-extrabold text-warning">{{ $infraStats['listrik'] }}%</span>
                    </div>
                    <div class="progress-bar-stat">
                        <div class="progress-bar-fill bg-warning" style="width: {{ $infraStats['listrik'] }}%;"></div>
                    </div>
                </div>

                <!-- Air Bersih -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark"><i class="bi bi-droplet-fill text-info me-2"></i> Pasokan Air Bersih (PDAM / Mata Air)</span>
                        <span class="small fw-extrabold text-info">{{ $infraStats['air_bersih'] }}%</span>
                    </div>
                    <div class="progress-bar-stat">
                        <div class="progress-bar-fill bg-info" style="width: {{ $infraStats['air_bersih'] }}%;"></div>
                    </div>
                </div>

                <!-- Telekomunikasi & 4G -->
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark"><i class="bi bi-wifi text-success me-2"></i> Jaringan Telekomunikasi & Internet 4G</span>
                        <span class="small fw-extrabold text-success">{{ $infraStats['internet'] }}%</span>
                    </div>
                    <div class="progress-bar-stat">
                        <div class="progress-bar-fill bg-success" style="width: {{ $infraStats['internet'] }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kesiapan Lahan -->
        <div class="col-lg-6">
            <div class="stat-chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="stat-chart-title">
                            <i class="bi bi-patch-check-fill text-info"></i> Status Kesiapan Lahan (Readiness Status)
                        </h3>
                        <small class="text-muted">Kesiapan status hukum dan fisik lahan untuk pembangunan konstruksi</small>
                    </div>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="lahanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Peluang Investasi Unggulan -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h3 class="h5 fw-bold text-dark mb-1">
                    <i class="bi bi-trophy-fill text-warning me-2"></i> 5 Peluang Investasi Unggulan Terbesar
                </h3>
                <small class="text-muted">Peluang strategis dengan estimasi dampak ekonomi daerah tertinggi</small>
            </div>
            <a href="{{ route('potensi.compare') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                <i class="bi bi-arrow-left-right me-1"></i> Buka Matriks Komparasi Lengkap
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama Peluang</th>
                        <th>Wilayah (Kelurahan/Kecamatan)</th>
                        <th>Sektor</th>
                        <th>Estimasi Modal (CAPEX)</th>
                        <th>Perkiraan ROI</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topPotensi as $item)
                        @php
                            $val = (float) ($item->nilai_estimasi_investasi ?: $item->nilai_estimasi ?: 0);
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->foto_utama)
                                        <img src="{{ asset('storage/' . $item->foto_utama) }}" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;" alt="{{ $item->nama }}">
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('potensi.detail', $item->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $item->nama }}
                                        </a>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Skala: {{ $item->skala_peluang ?: 'Menengah' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                <span class="small">{{ $item->kelurahan ? $item->kelurahan->nama . ', Kec. ' . ($item->kelurahan->kecamatan ?: '-') : 'Kab. Sangihe' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                    {{ $item->jenis_potensi ?: 'Sektor Unggulan' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-extrabold text-success">
                                    @if($val >= 1000000000)
                                        Rp {{ number_format($val / 1000000000, 1, ',', '.') }} Miliar
                                    @elseif($val >= 1000000)
                                        Rp {{ number_format($val / 1000000, 1, ',', '.') }} Juta
                                    @else
                                        Rp {{ number_format($val, 0, ',', '.') }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle fw-bold rounded-pill">
                                    {{ $item->roi_estimasi ? number_format($item->roi_estimasi, 1, ',', '.') . '%' : '15.0%' }} / Thn
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('potensi.detail', $item->id) }}#kalkulator-investasi" class="btn btn-sm btn-info text-dark rounded-pill px-2.5 py-1" title="Simulasi ROI">
                                        <i class="bi bi-calculator"></i>
                                    </a>
                                    <a href="{{ route('potensi.detail', $item->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1">
                                        Detail <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mengapa Berinvestasi di Kepulauan Sangihe (Strategic Value Proposition) -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-stars text-warning"></i> Keunggulan Strategis Berinvestasi di Kepulauan Sangihe
            </h4>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="advantage-box">
                <div class="p-2.5 rounded-3 bg-primary bg-opacity-10 text-primary d-inline-flex mb-3">
                    <i class="bi bi-globe-americas fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Geostrategis Perbatasan Pasifik</h6>
                <p class="small text-muted mb-0">
                    Posisi terdepan di Alur Laut Kepulauan Indonesia (ALKI II) dan gerbang perdagangan maritim langsung menuju Filipina Selatan dan Pasifik.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="advantage-box">
                <div class="p-2.5 rounded-3 bg-success bg-opacity-10 text-success d-inline-flex mb-3">
                    <i class="bi bi-water fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Kekayaan Maritim & Tuna Melimpah</h6>
                <p class="small text-muted mb-0">
                    Zona Wilayah Pengelolaan Perikanan (WPP 716) Laut Sulawesi dengan potensi perikanan tangkap dan budidaya laut bernilai ekspor tinggi.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="advantage-box">
                <div class="p-2.5 rounded-3 bg-warning bg-opacity-10 text-warning d-inline-flex mb-3">
                    <i class="bi bi-tree-fill fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Pala Sangihe Bersertifikasi IG</h6>
                <p class="small text-muted mb-0">
                    Komoditas rempah pala organik berstandar internasional dengan sertifikasi Indikasi Geografis (IG) resmi yang diminati pasar Eropa & Asia.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="advantage-box">
                <div class="p-2.5 rounded-3 bg-info bg-opacity-10 text-info d-inline-flex mb-3">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Kemudahan Berusaha & Insentif</h6>
                <p class="small text-muted mb-0">
                    DPMPTSP Kabupaten Kepulauan Sangihe menyediakan pendampingan satu pintu OSS-RBA, kepastian ruang, dan fasilitasi insentif daerah.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Distribusi Sektor
    const sektorLabels = {!! json_encode($sektorStats->pluck('jenis_potensi')) !!};
    const sektorValues = {!! json_encode($sektorStats->pluck('total_nilai')) !!};
    const sektorColors = [
        '#2563eb', '#10b981', '#f59e0b', '#06b6d4', '#8b5cf6', '#ec4899', '#f97316', '#64748b'
    ];

    const ctxSektor = document.getElementById('sektorChart');
    if (ctxSektor && window.Chart) {
        new Chart(ctxSektor, {
            type: 'doughnut',
            data: {
                labels: sektorLabels,
                datasets: [{
                    data: sektorValues,
                    backgroundColor: sektorColors.slice(0, sektorLabels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed;
                                let formatted = 'Rp ' + (val / 1000000000).toFixed(1) + ' Miliar';
                                if (val < 1000000000) formatted = 'Rp ' + (val / 1000000).toFixed(0) + ' Juta';
                                return context.label + ': ' + formatted;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Chart Sebaran per Kecamatan
    const kecLabels = {!! json_encode($kecamatanStats->pluck('kecamatan')) !!};
    const kecCounts = {!! json_encode($kecamatanStats->pluck('count')) !!};

    const ctxKec = document.getElementById('kecamatanChart');
    if (ctxKec && window.Chart) {
        new Chart(ctxKec, {
            type: 'bar',
            data: {
                labels: kecLabels,
                datasets: [{
                    label: 'Jumlah Peluang',
                    data: kecCounts,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { stepSize: 1, font: { size: 10 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        ticks: { font: { size: 10, weight: '600' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 3. Chart Kesiapan Lahan
    const lahanLabels = {!! json_encode($kesiapanLahan->map(fn($k) => ucwords(str_replace('_', ' ', $k->status_kesiapan_lahan)))) !!};
    const lahanCounts = {!! json_encode($kesiapanLahan->pluck('count')) !!};

    const ctxLahan = document.getElementById('lahanChart');
    if (ctxLahan && window.Chart) {
        new Chart(ctxLahan, {
            type: 'pie',
            data: {
                labels: lahanLabels.length ? lahanLabels : ['Siap Bangun (Clean & Clear)', 'Tahap Penyiapan', 'Masterplan'],
                datasets: [{
                    data: lahanCounts.length ? lahanCounts : [18, 8, 3],
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#94a3b8'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });
    }

    // 4. Animated CountUp for KPI Cards
    function animateCountUp(elementId, targetValue, isCurrency, duration = 1400) {
        const el = document.getElementById(elementId);
        if (!el) return;

        let startTime = null;
        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const currentVal = progress * targetValue;

            if (isCurrency) {
                el.innerText = 'Rp ' + currentVal.toFixed(1).replace('.', ',') + ' M';
            } else {
                el.innerText = Math.floor(currentVal);
            }

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                if (isCurrency) {
                    el.innerText = 'Rp ' + targetValue.toFixed(1).replace('.', ',') + ' M';
                } else {
                    el.innerText = targetValue;
                }
            }
        }
        window.requestAnimationFrame(step);
    }

    animateCountUp('statPotensiPublik', {{ $totalPotensi }}, false);
    animateCountUp('statNilaiPublik', {{ round(($totalNilai ?: 0) / 1000000000, 1) }}, true);
    animateCountUp('statKelurahanPublik', {{ $totalKelurahan }}, false);
    animateCountUp('statMinatPublik', {{ $totalMinat }}, false);
});
</script>
@endpush

