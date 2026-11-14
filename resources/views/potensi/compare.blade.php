@extends('layouts.app')

@section('title', 'Bandingkan Peluang Investasi - GIS Potensi Investasi Sangihe')

@push('styles')
<style>
    .compare-container {
        max-width: 1300px;
        margin: 0 auto;
    }
    .compare-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .compare-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }
    .compare-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }
    .compare-table th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        vertical-align: middle;
    }
    .compare-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }
    .category-header {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px !important;
        border-left: 4px solid #2563eb;
    }
    .criteria-label {
        font-weight: 600;
        color: #475569;
        width: 25%;
        background: #fcfdfe;
    }
    .infra-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .infra-ready {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .infra-notready {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    @media print {
        .navbar-gis, .breadcrumb, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .compare-table {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 compare-container">
    <!-- Breadcrumb & Nav -->
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ url('/map') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
            <i class="bi bi-arrow-left me-1"></i> {{ __('messages.back_to_map') }}
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/map') }}">{{ __('messages.interactive_map') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.compare') }}</li>
            </ol>
        </nav>
    </div>

    <!-- Header Hero -->
    <div class="p-4 rounded-4 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">
                        <i class="bi bi-award-fill me-1"></i> {{ app()->getLocale() === 'en' ? 'Innovation Feature' : 'Fitur Inovasi' }}
                    </span>
                    <span class="badge bg-primary rounded-pill px-3 py-1">
                        {{ app()->getLocale() === 'en' ? 'Multi-Criteria Matrix' : 'Matriks Multi-Kriteria' }}
                    </span>
                </div>
                <h1 class="h3 fw-bold mb-1">{{ __('messages.compare_title') }}</h1>
                <p class="text-white-50 small mb-0">
                    {{ __('messages.compare_desc') }}
                </p>
            </div>
            <div class="d-flex gap-2 no-print">
                <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-printer-fill text-warning"></i>
                    <span>{{ __('messages.print_matrix') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Opportunity Selector Bars (Allows changing who to compare) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white no-print">
        <div class="row align-items-center g-3">
            <div class="col-lg-3">
                <label class="form-label small fw-bold text-dark mb-0 d-flex align-items-center gap-1">
                    <i class="bi bi-shuffle text-primary"></i> {{ __('messages.select_compare') }}
                </label>
                <small class="text-muted d-block" style="font-size: 0.72rem;">{{ __('messages.max_3_compare') }}</small>
            </div>
            <div class="col-lg-9">
                <form action="{{ route('potensi.compare') }}" method="GET" id="compareForm" class="row g-2">
                    @for($i = 0; $i < 3; $i++)
                        @php
                            $currentId = $selectedIds[$i] ?? '';
                        @endphp
                        <div class="col-md-4">
                            <select name="ids[]" class="form-select form-select-sm rounded-3" onchange="document.getElementById('compareForm').submit()">
                                <option value="">-- Pilih Peluang #{{ $i + 1 }} --</option>
                                @foreach($allOpportunities as $opt)
                                    <option value="{{ $opt->id }}" {{ $currentId == $opt->id ? 'selected' : '' }}>
                                        {{ $opt->nama }} ({{ $opt->jenis_potensi }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endfor
                </form>
            </div>
        </div>
    </div>

    @if($items->count() < 2)
        <div class="alert alert-info rounded-4 p-4 text-center">
            <i class="bi bi-info-circle-fill fs-2 text-info d-block mb-2"></i>
            <h5 class="fw-bold">Pilih Minimal 2 Peluang Investasi</h5>
            <p class="text-muted small mb-3">Silakan gunakan pilihan dropdown di atas untuk memilih minimal 2 peluang yang ingin Anda bandingkan secara langsung.</p>
        </div>
    @else
        <!-- Multi-Criteria Head-to-Head Table -->
        <div class="table-responsive compare-table mb-5">
            <table class="table table-bordered mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="criteria-label">Kriteria Evaluasi</th>
                        @foreach($items as $item)
                            <th class="text-center" style="width: {{ 75 / $items->count() }}%;">
                                <div class="p-2">
                                    @if($item->foto_utama)
                                        <div class="rounded-3 overflow-hidden shadow-sm mb-2" style="height: 140px;">
                                            <img src="{{ asset('storage/' . $item->foto_utama) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $item->nama }}">
                                        </div>
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted mb-2 border" style="height: 140px;">
                                            <i class="bi bi-image fs-1 text-secondary opacity-50"></i>
                                        </div>
                                    @endif
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 mb-1" style="font-size: 0.72rem;">
                                        {{ $item->jenis_potensi ?: 'Sektor Unggulan' }}
                                    </span>
                                    <h5 class="fw-bold text-dark mb-1 fs-6">{{ $item->nama }}</h5>
                                    <small class="text-muted d-block mb-3">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $item->kelurahan ? $item->kelurahan->nama . ', Kec. ' . ($item->kelurahan->kecamatan ?: '-') : 'Kab. Kepulauan Sangihe' }}
                                    </small>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center no-print">
                                        <a href="{{ route('potensi.detail', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                        <a href="{{ route('potensi.detail', $item->id) }}#kalkulator-investasi" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-calculator me-1"></i> Simulasi ROI
                                        </a>
                                    </div>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- 1. KELAYAKAN FINANSIAL & PROYEKSI -->
                    <tr>
                        <td colspan="{{ $items->count() + 1 }}" class="category-header">
                            <i class="bi bi-cash-stack text-primary me-2"></i> 1. Parameter Finansial & Potensi Keuntungan
                        </td>
                    </tr>
                    <tr>
                        <td class="criteria-label">Nilai Estimasi Investasi (CAPEX)</td>
                        @foreach($items as $item)
                            @php
                                $val = (float) ($item->nilai_estimasi_investasi ?: $item->nilai_estimasi ?: 0);
                            @endphp
                            <td class="text-center">
                                @php
                                    $isEnLocale = app()->getLocale() === 'en';
                                    $bWord = $isEnLocale ? __('messages.currency_billion') : 'Miliar';
                                    $mWord = $isEnLocale ? __('messages.currency_million') : 'Juta';
                                    $decP = $isEnLocale ? '.' : ',';
                                    $thousP = $isEnLocale ? ',' : '.';
                                @endphp
                                <div class="fs-5 fw-extrabold text-success">
                                    @if($val >= 1000000000)
                                        Rp {{ number_format($val / 1000000000, 1, $decP, $thousP) }} {{ $bWord }}
                                    @elseif($val >= 1000000)
                                        Rp {{ number_format($val / 1000000, 1, $decP, $thousP) }} {{ $mWord }}
                                    @else
                                        Rp {{ number_format($val, 0, $decP, $thousP) }}
                                    @endif
                                </div>
                                <span class="badge bg-light text-secondary border rounded-pill text-capitalize mt-1" style="font-size: 0.72rem;">
                                    {{ $item->skala_peluang ?: 'Menengah' }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Perkiraan ROI Tahunan (%)</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <div class="fs-5 fw-bold text-primary">
                                    {{ $item->roi_estimasi ? number_format($item->roi_estimasi, 1, ',', '.') . '%' : '15.0% (Est.)' }}
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Return on Investment per tahun</small>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Estimasi Payback Period (BEP)</td>
                        @foreach($items as $item)
                            @php
                                $roi = (float) ($item->roi_estimasi ?: 15.0);
                                $bep = $roi > 0 ? (100 / $roi) : 0;
                            @endphp
                            <td class="text-center">
                                <span class="fw-bold text-dark fs-6">{{ $bep > 0 ? number_format($bep, 1, ',', '.') . ' Tahun' : '-' }}</span>
                                <div class="mt-1">
                                    @if($bep <= 4)
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill">Pengembalian Kilat</span>
                                    @elseif($bep <= 7)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Standar Sehat</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Jangka Menengah</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    <!-- 2. STATUS LAHAN & LEGALITAS -->
                    <tr>
                        <td colspan="{{ $items->count() + 1 }}" class="category-header">
                            <i class="bi bi-file-earmark-check text-success me-2"></i> 2. Status Kesiapan Lahan & Legalitas
                        </td>
                    </tr>
                    <tr>
                        <td class="criteria-label">Kesiapan Lahan (Readiness)</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                @if(str_contains(strtolower($item->status_kesiapan_lahan ?? ''), 'siap') || str_contains(strtolower($item->status_lahan ?? ''), 'tersedia'))
                                    <span class="badge bg-success px-3 py-1.5 rounded-pill fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i> {{ ucwords(str_replace('_', ' ', $item->status_kesiapan_lahan ?: 'Siap Digunakan')) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-semibold">
                                        <i class="bi bi-hourglass me-1"></i> {{ ucwords(str_replace('_', ' ', $item->status_kesiapan_lahan ?: 'Tahap Penyiapan')) }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Status Kepemilikan Lahan</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <span class="fw-semibold text-dark">{{ $item->status_lahan ?: 'Aset Pemkab / Hak Adat' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Status Tata Ruang & Perizinan</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill">
                                    {{ $item->status_perizinan ?: 'Sesuai Pola Ruang RTRW Sangihe' }}
                                </span>
                            </td>
                        @endforeach
                    </tr>

                    <!-- 3. KESIAPAN INFRASTRUKTUR DASAR -->
                    <tr>
                        <td colspan="{{ $items->count() + 1 }}" class="category-header">
                            <i class="bi bi-lightning-charge text-warning me-2"></i> 3. Kesiapan Utilitas & Infrastruktur
                        </td>
                    </tr>
                    <tr>
                        <td class="criteria-label">Akses Jalan Logistik</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                @if($item->akses_jalan)
                                    <span class="infra-badge infra-ready"><i class="bi bi-check-circle-fill"></i> Tersedia (Aspal/Truk)</span>
                                @else
                                    <span class="infra-badge infra-notready"><i class="bi bi-x-circle-fill"></i> Perlu Peningkatan</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Pasokan Tenaga Listrik (PLN)</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                @if($item->listrik)
                                    <span class="infra-badge infra-ready"><i class="bi bi-check-circle-fill"></i> Terhubung Grid PLN</span>
                                @else
                                    <span class="infra-badge infra-notready"><i class="bi bi-x-circle-fill"></i> Belum Masuk Jaringan</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Pasokan Air Bersih</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                @if($item->air_bersih)
                                    <span class="infra-badge infra-ready"><i class="bi bi-check-circle-fill"></i> Sumber Tersedia (PDAM/Mata Air)</span>
                                @else
                                    <span class="infra-badge infra-notready"><i class="bi bi-x-circle-fill"></i> Perlu Pengeboran/Pipa</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Jaringan Internet & Telekomunikasi</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                @if($item->internet)
                                    <span class="infra-badge infra-ready"><i class="bi bi-check-circle-fill"></i> 4G LTE Sinyal Kuat</span>
                                @else
                                    <span class="infra-badge infra-notready"><i class="bi bi-x-circle-fill"></i> Spot Terbatas</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- 4. KONEKTIVITAS LOGISTIK & TRANSPORTASI -->
                    <tr>
                        <td colspan="{{ $items->count() + 1 }}" class="category-header">
                            <i class="bi bi-truck text-info me-2"></i> 4. Jarak & Aksesibilitas Logistik Sangihe
                        </td>
                    </tr>
                    <tr>
                        <td class="criteria-label">Jarak ke Pelabuhan Laut Tahuna</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <span class="fw-bold fs-6 text-dark">
                                    {{ $item->jarak_pelabuhan ? number_format($item->jarak_pelabuhan, 1, ',', '.') . ' km' : '± 5.0 km' }}
                                </span>
                                <div class="text-muted" style="font-size: 0.72rem;">Pusat Kapal Kontainer & Tol Laut</div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Jarak ke Bandara Naha Sangihe</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <span class="fw-bold fs-6 text-dark">
                                    {{ $item->jarak_bandara ? number_format($item->jarak_bandara, 1, ',', '.') . ' km' : '± 28.0 km' }}
                                </span>
                                <div class="text-muted" style="font-size: 0.72rem;">Kargo Udara & Penerbangan Perintis</div>
                            </td>
                        @endforeach
                    </tr>

                    <!-- 5. ANALISIS SWOT HEAD-TO-HEAD -->
                    <tr>
                        <td colspan="{{ $items->count() + 1 }}" class="category-header">
                            <i class="bi bi-grid-1x2 text-secondary me-2"></i> 5. Analisis SWOT Komparatif
                        </td>
                    </tr>
                    <tr>
                        <td class="criteria-label">Kekuatan Utama (Strengths)</td>
                        @foreach($items as $item)
                            <td>
                                <div class="p-2 rounded-3 bg-success-subtle text-success-emphasis border border-success-subtle small">
                                    {{ $item->analisis_swot['strengths'] ?? $item->analisis_swot['kekuatan'] ?? 'Potensi sumber daya melimpah dan dukungan masyarakat kondusif.' }}
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="criteria-label">Tantangan & Mitigasi (Threats)</td>
                        @foreach($items as $item)
                            <td>
                                <div class="p-2 rounded-3 bg-warning-subtle text-warning-emphasis border border-warning-subtle small">
                                    {{ $item->analisis_swot['threats'] ?? $item->analisis_swot['tantangan'] ?? 'Jarak rantai pasok kepulauan dimitigasi dengan subsidi tol laut Pemkab.' }}
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    <!-- 6. KESIMPULAN & REKOMENDASI -->
                    <tr>
                        <td class="criteria-label">Rekomendasi DPMPTSP</td>
                        @foreach($items as $item)
                            <td class="text-center">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="fw-bold text-primary mb-1">Prioritas Pengembangan Daerah</div>
                                    <p class="small text-muted mb-3" style="font-size: 0.8rem;">
                                        Sangat direkomendasikan untuk skema kemitraan Sewa Lahan BOT atau Investasi Mandiri dengan fasilitas asistensi OSS-RBA penuh.
                                    </p>
                                    <a href="{{ route('potensi.detail', $item->id) }}" class="btn btn-warning w-100 rounded-pill fw-bold btn-sm shadow-sm">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Prospektus & e-LoI
                                    </a>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

