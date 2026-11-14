@extends('layouts.app')

@section('title', $potensi->nama . ' - Prospektus Potensi Investasi')

@push('styles')
<style>
    .prospectus-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        color: white;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .prospectus-hero::after {
        content: '';
        position: absolute;
        right: -40px;
        bottom: -40px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, rgba(37,99,235,0) 70%);
        border-radius: 50%;
    }

    .metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .metric-card .metric-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 6px;
    }

    .metric-card .metric-val {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .info-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .info-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }

    .infra-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        margin-bottom: 8px;
    }

    .infra-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    #location-map {
        height: 300px;
        width: 100%;
        border-radius: 12px;
        z-index: 10;
    }

    .swot-box {
        border-radius: 10px;
        padding: 14px;
        height: 100%;
    }
    .swot-s { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .swot-w { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .swot-o { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
    .swot-t { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

    /* Kalkulator Investasi Styles */
    .sim-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .sim-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 20px 24px;
    }
    .sim-slider-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 14px;
    }
    .sim-slider {
        width: 100%;
        height: 8px;
        border-radius: 4px;
        outline: none;
        accent-color: #2563eb;
        cursor: pointer;
    }
    .sim-metric-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .sim-metric-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }
    .sim-chip-btn {
        font-size: 0.74rem;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s;
    }
    .sim-chip-btn:hover, .sim-chip-btn.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Back -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ url('/map') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Peta Interaktif
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/map') }}">GIS Investasi</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/map?jenis_potensi=' . urlencode($potensi->jenis_potensi)) }}">{{ $potensi->jenis_potensi }}</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $potensi->nama }}</li>
            </ol>
        </nav>
    </div>

    <!-- Hero Card -->
    <div class="prospectus-hero shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-primary px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-tag-fill me-1"></i> {{ $potensi->jenis_potensi ?: 'Sektor Unggulan' }}
                    </span>
                    <span class="badge bg-light text-dark px-3 py-1 rounded-pill fw-semibold text-capitalize" style="font-size: 0.8rem;">
                        Skala: {{ $potensi->skala_peluang ?: 'Menengah' }}
                    </span>
                    @if($potensi->status_kesiapan_lahan)
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1 rounded-pill fw-semibold text-capitalize" style="font-size: 0.8rem;">
                            Kesiapan: {{ str_replace('_', ' ', $potensi->status_kesiapan_lahan) }}
                        </span>
                    @endif
                </div>

                <h1 class="h2 fw-bold text-white mb-2">{{ $potensi->nama }}</h1>
                <p class="text-white-50 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    <span>{{ $potensi->kelurahan ? $potensi->kelurahan->nama . ', Kec. ' . ($potensi->kelurahan->kecamatan ?: '-') : 'Kabupaten Kepulauan' }}</span>
                </p>
            </div>

            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0 d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('potensi.pdf', $potensi->id) }}" target="_blank" class="btn btn-outline-light px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                    <span>Unduh Dokumen Prospektus (PDF)</span>
                </a>
                <button type="button" class="btn btn-outline-light px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2" onclick="copyProspectusLink(this)" title="Salin Tautan Prospektus">
                    <i class="bi bi-share-fill text-info fs-6"></i>
                    <span>Bagikan</span>
                </button>
                <a href="#kalkulator-investasi" class="btn btn-outline-info px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-calculator-fill text-info fs-5"></i>
                    <span>Simulasi ROI</span>
                </a>
                <a href="{{ url('/map') }}?open3d={{ $potensi->id }}" class="btn btn-outline-warning px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-box-fill text-warning fs-5"></i>
                    <span>Model 3D</span>
                </a>
                <button class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow d-inline-flex align-items-center gap-2" onclick="openMinatModal()">
                    <i class="bi bi-envelope-paper-heart-fill"></i>
                    <span>Daftar Minat</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 Key Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-cash-stack text-success me-1"></i> Nilai Investasi</div>
                <div class="metric-val text-success">
                    @php
                        $nilai = (float) ($potensi->nilai_estimasi_investasi ?: $potensi->nilai_estimasi);
                    @endphp
                    @if($nilai >= 1000000000)
                        Rp {{ number_format($nilai / 1000000000, 1, ',', '.') }} <span class="fs-6 fw-semibold text-muted">Miliar</span>
                    @elseif($nilai >= 1000000)
                        Rp {{ number_format($nilai / 1000000, 1, ',', '.') }} <span class="fs-6 fw-semibold text-muted">Juta</span>
                    @else
                        Rp {{ number_format($nilai, 0, ',', '.') }}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-graph-up-arrow text-primary me-1"></i> Perkiraan ROI</div>
                <div class="metric-val text-primary">
                    {{ $potensi->roi_estimasi ? $potensi->roi_estimasi . '%' : 'N/A' }}
                    @if($potensi->roi_estimasi)
                        <span class="fs-6 fw-semibold text-muted">/ tahun</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-shield-check text-info me-1"></i> Status Perizinan</div>
                <div class="metric-val fs-5 text-dark text-capitalize">
                    {{ str_replace('_', ' ', $potensi->status_perizinan ?: 'Tersedia') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-map text-warning me-1"></i> Status Lahan</div>
                <div class="metric-val fs-5 text-dark text-capitalize">
                    {{ str_replace('_', ' ', $potensi->status_lahan ?: 'Tersedia') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left: Prospectus Details -->
        <div class="col-lg-8">
            <!-- Deskripsi & Gambaran Umum -->
            <div class="info-section">
                <h3 class="info-section-title">
                    <i class="bi bi-file-text-fill text-primary"></i> Gambaran Umum Peluang
                </h3>
                <p class="text-secondary leading-relaxed mb-3" style="font-size: 0.95rem; line-height: 1.7;">
                    {{ $potensi->deskripsi ?: 'Peluang investasi strategis di Kabupaten Kepulauan dengan potensi komoditas unggulan dan prospek pertumbuhan ekonomi yang menjanjikan.' }}
                </p>

                @if($potensi->komoditas_unggulan)
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-stars fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.72rem;">Komoditas Unggulan</small>
                            <span class="fw-bold text-dark fs-6">{{ $potensi->komoditas_unggulan }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Infrastruktur & Fasilitas Pendukung -->
            <div class="info-section">
                <h3 class="info-section-title">
                    <i class="bi bi-lightning-charge-fill text-warning"></i> Ketersediaan Infrastruktur & Akses
                </h3>

                @php
                    $jalan = (bool) ($potensi->akses_jalan ?? $potensi->kelurahan?->akses_jalan);
                    $listrik = (bool) ($potensi->listrik ?? $potensi->kelurahan?->listrik);
                    $air = (bool) ($potensi->air_bersih ?? $potensi->kelurahan?->air_bersih);
                    $internet = (bool) ($potensi->internet ?? $potensi->kelurahan?->internet);
                @endphp

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <div class="infra-item">
                            <div class="infra-icon {{ $jalan ? 'bg-success text-white' : 'bg-secondary-subtle text-muted' }}">
                                <i class="bi bi-signpost-split"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block fw-semibold small">Akses Jalan</span>
                                <small class="{{ $jalan ? 'text-success fw-bold' : 'text-muted' }}">
                                    {{ $jalan ? 'Sudah Tersedia / Aspal' : 'Belum Tersedia' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="infra-item">
                            <div class="infra-icon {{ $listrik ? 'bg-success text-white' : 'bg-secondary-subtle text-muted' }}">
                                <i class="bi bi-lightning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block fw-semibold small">Jaringan Listrik PLN</span>
                                <small class="{{ $listrik ? 'text-success fw-bold' : 'text-muted' }}">
                                    {{ $listrik ? 'Tersedia & Stabil' : 'Belum Tersedia' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="infra-item">
                            <div class="infra-icon {{ $air ? 'bg-success text-white' : 'bg-secondary-subtle text-muted' }}">
                                <i class="bi bi-droplet"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block fw-semibold small">Sumber Air Bersih</span>
                                <small class="{{ $air ? 'text-success fw-bold' : 'text-muted' }}">
                                    {{ $air ? 'Tersedia PDAM / Mata Air' : 'Belum Tersedia' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="infra-item">
                            <div class="infra-icon {{ $internet ? 'bg-success text-white' : 'bg-secondary-subtle text-muted' }}">
                                <i class="bi bi-wifi"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="d-block fw-semibold small">Jaringan Internet / 4G</span>
                                <small class="{{ $internet ? 'text-success fw-bold' : 'text-muted' }}">
                                    {{ $internet ? 'Terjangkau 4G LTE' : 'Terbatas' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jarak Logistik -->
                @if($potensi->jarak_pelabuhan || $potensi->jarak_bandara)
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-3">
                            @if($potensi->jarak_pelabuhan)
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-water text-primary fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Jarak ke Pelabuhan Laut</small>
                                        <span class="fw-bold">{{ $potensi->jarak_pelabuhan }} km</span>
                                    </div>
                                </div>
                            @endif
                            @if($potensi->jarak_bandara)
                                <div class="col-sm-6 d-flex align-items-center gap-2">
                                    <i class="bi bi-airplane text-primary fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Jarak ke Bandara</small>
                                        <span class="fw-bold">{{ $potensi->jarak_bandara }} km</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- SWOT Analysis jika ada -->
            @if(!empty($potensi->analisis_swot) && is_array($potensi->analisis_swot))
                <div class="info-section">
                    <h3 class="info-section-title">
                        <i class="bi bi-grid-1x2-fill text-info"></i> Analisis SWOT Peluang
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="swot-box swot-s">
                                <h6 class="fw-bold mb-2"><i class="bi bi-shield-plus me-1"></i> Kekuatan (Strengths)</h6>
                                <p class="small mb-0">{{ $potensi->analisis_swot['strengths'] ?? $potensi->analisis_swot['kekuatan'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="swot-box swot-w">
                                <h6 class="fw-bold mb-2"><i class="bi bi-shield-minus me-1"></i> Kelemahan (Weaknesses)</h6>
                                <p class="small mb-0">{{ $potensi->analisis_swot['weaknesses'] ?? $potensi->analisis_swot['kelemahan'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="swot-box swot-o">
                                <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb me-1"></i> Peluang (Opportunities)</h6>
                                <p class="small mb-0">{{ $potensi->analisis_swot['opportunities'] ?? $potensi->analisis_swot['peluang'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="swot-box swot-t">
                                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-octagon me-1"></i> Tantangan (Threats)</h6>
                                <p class="small mb-0">{{ $potensi->analisis_swot['threats'] ?? $potensi->analisis_swot['tantangan'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kalkulator Simulasi Kelayakan Investasi & ROI Interaktif -->
            @php
                $modalDefault = (float) ($potensi->nilai_estimasi_investasi ?: $potensi->nilai_estimasi ?: 500000000);
                $roiDefault = (float) ($potensi->roi_estimasi ?: 15.0);
            @endphp
            <div class="info-section sim-card p-0 mb-4" id="kalkulator-investasi">
                <div class="sim-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2.5 rounded-3 bg-primary bg-opacity-25 text-info fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-calculator-fill"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="h5 fw-bold text-white mb-0">Simulasi Kelayakan Investasi & ROI Interaktif</h3>
                                <span class="badge bg-warning text-dark fw-bold rounded-pill" style="font-size: 0.7rem;">Inovasi Pintar</span>
                            </div>
                            <small class="text-white-50">Modelkan simulasi modal, proyeksi laba, payback period (BEP), dan serapan tenaga kerja secara real-time</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1" onclick="resetSimulation()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Simulasi
                    </button>
                </div>

                <div class="p-4 bg-white">
                    <div class="row g-4">
                        <!-- Sisi Kiri: Parameter Input Interaktif -->
                        <div class="col-12 col-xl-6">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-sliders2 text-primary"></i> 1. Parameter Penanaman Modal
                            </h6>

                            <!-- Nilai Modal (CAPEX) -->
                            <div class="sim-slider-container">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-bold text-secondary mb-0">Alokasi Modal Investasi (CAPEX):</label>
                                    <span id="simModalDisplay" class="fw-bold text-primary fs-6">Rp 0</span>
                                </div>
                                <input type="range" class="sim-slider my-2" id="simModalRange" min="50000000" max="50000000000" step="50000000" value="{{ $modalDefault }}">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.7rem;">
                                    <span>Rp 50 Jt</span>
                                    <span>Rp 10 M</span>
                                    <span>Rp 50 M+</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <span class="text-muted small me-1 align-self-center" style="font-size: 0.72rem;">Preset:</span>
                                    @if($modalDefault > 0)
                                        <button type="button" class="sim-chip-btn active" id="chipPresetData" onclick="setModalValue({{ $modalDefault }}, this)">Sesuai Data ({{ $modalDefault >= 1000000000 ? 'Rp ' . number_format($modalDefault/1000000000, 1, ',', '.') . ' M' : 'Rp ' . number_format($modalDefault/1000000, 0, ',', '.') . ' Jt' }})</button>
                                    @endif
                                    <button type="button" class="sim-chip-btn" onclick="setModalValue(500000000, this)">Rp 500 Jt</button>
                                    <button type="button" class="sim-chip-btn" onclick="setModalValue(2000000000, this)">Rp 2 M</button>
                                    <button type="button" class="sim-chip-btn" onclick="setModalValue(5000000000, this)">Rp 5 M</button>
                                    <button type="button" class="sim-chip-btn" onclick="setModalValue(15000000000, this)">Rp 15 M</button>
                                </div>
                            </div>

                            <!-- Target ROI % -->
                            <div class="sim-slider-container">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-bold text-secondary mb-0">Asumsi ROI Tahunan (% Return on Investment):</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="simRoiBadge" class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill" style="font-size: 0.7rem;">Moderat</span>
                                        <span id="simRoiDisplay" class="fw-bold text-success fs-6">{{ number_format($roiDefault, 1, ',', '.') }}%</span>
                                    </div>
                                </div>
                                <input type="range" class="sim-slider my-2" id="simRoiRange" min="5" max="50" step="0.5" value="{{ $roiDefault }}">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.7rem;">
                                    <span>5% (Konservatif)</span>
                                    <span>20% (Moderat)</span>
                                    <span>50% (Agresif)</span>
                                </div>
                            </div>

                            <!-- Model Kerjasama -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Skema Kemitraan yang Direncanakan:</label>
                                <select class="form-select form-select-sm" id="simModelKerjasama">
                                    <option value="sewa_lahan" selected>Sewa Lahan / Aset Pemkab (BOT/BTO - Rekomendasi)</option>
                                    <option value="kpbu">KPBU (Kerjasama Pemerintah & Swasta)</option>
                                    <option value="joint_venture">Joint Venture (Kemitraan Usaha BUMD / Koperasi)</option>
                                    <option value="mandiri_100">Investasi Mandiri 100% Swasta</option>
                                </select>
                            </div>

                            <!-- Tenor Proyek -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Horizon Investasi (Jangka Waktu Proyek):</label>
                                <div class="btn-group w-100" role="group" id="simTenorGroup">
                                    <input type="radio" class="btn-check" name="simTenor" id="tenor3" value="3" autocomplete="off" onchange="runSimulation()">
                                    <label class="btn btn-outline-secondary btn-sm py-1.5" for="tenor3">3 Thn</label>

                                    <input type="radio" class="btn-check" name="simTenor" id="tenor5" value="5" autocomplete="off" checked onchange="runSimulation()">
                                    <label class="btn btn-outline-secondary btn-sm py-1.5" for="tenor5">5 Thn</label>

                                    <input type="radio" class="btn-check" name="simTenor" id="tenor7" value="7" autocomplete="off" onchange="runSimulation()">
                                    <label class="btn btn-outline-secondary btn-sm py-1.5" for="tenor7">7 Thn</label>

                                    <input type="radio" class="btn-check" name="simTenor" id="tenor10" value="10" autocomplete="off" onchange="runSimulation()">
                                    <label class="btn btn-outline-secondary btn-sm py-1.5" for="tenor10">10 Thn</label>

                                    <input type="radio" class="btn-check" name="simTenor" id="tenor15" value="15" autocomplete="off" onchange="runSimulation()">
                                    <label class="btn btn-outline-secondary btn-sm py-1.5" for="tenor15">15 Thn</label>
                                </div>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Output Indikator & Grafik Break Even Point -->
                        <div class="col-12 col-xl-6">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-speedometer text-success"></i> 2. Hasil Proyeksi Kelayakan Finansial
                            </h6>

                            <!-- 4 Kartu Metrik Hasil -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="sim-metric-box border-start border-4 border-success">
                                        <div class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-cash-stack text-success me-1"></i> Laba Bersih Tahunan</div>
                                        <div class="fw-bold text-success fs-6 mt-1" id="simLabaTahunanDisplay">Rp 0</div>
                                        <div class="text-muted" style="font-size: 0.68rem;">Rata-rata per tahun</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="sim-metric-box border-start border-4 border-primary">
                                        <div class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-hourglass-split text-primary me-1"></i> Payback Period (BEP)</div>
                                        <div class="fw-bold text-primary fs-6 mt-1" id="simBepDisplay">0 Tahun</div>
                                        <div class="text-muted" style="font-size: 0.68rem;" id="simBepStatusBadge">Standar</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="sim-metric-box border-start border-4 border-info">
                                        <div class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-graph-up-arrow text-info me-1"></i> Total Laba Horizon</div>
                                        <div class="fw-bold text-dark fs-6 mt-1" id="simTotalProfitDisplay">Rp 0</div>
                                        <div class="text-muted" style="font-size: 0.68rem;" id="simTotalProfitSubtext">Akumulasi tenor</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="sim-metric-box border-start border-4 border-warning">
                                        <div class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-people-fill text-warning me-1"></i> Serapan Naker Lokal</div>
                                        <div class="fw-bold text-dark fs-6 mt-1" id="simNakerDisplay">± 0 Orang</div>
                                        <div class="text-muted" style="font-size: 0.68rem;">Dampak sosial ekonomi</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Visualisasi Arus Kas Kumulatif -->
                            <div class="border rounded-3 p-3 bg-light mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-secondary"><i class="bi bi-bar-chart-line me-1"></i> Proyeksi Arus Kas Kumulatif (BEP)</span>
                                    <span class="badge bg-secondary-subtle text-secondary border small" style="font-size: 0.68rem;" id="simChartCaption">Horizon 5 Tahun</span>
                                </div>
                                <div style="position: relative; height: 180px; width: 100%;">
                                    <canvas id="simCashFlowChart"></canvas>
                                </div>
                            </div>

                            <!-- Tombol Aksi Integrasi ke Formulir Minat / LoI -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-warning py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="applySimulationToLoI()">
                                    <i class="bi bi-envelope-paper-heart-fill"></i>
                                    <span>Gunakan Hasil Simulasi ke Formulir Minat (e-LoI)</span>
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill w-100" onclick="printSimulationSummary()">
                                        <i class="bi bi-printer me-1"></i> Cetak Ringkasan
                                    </button>
                                    <a href="https://wa.me/6281234567890?text=Halo%20DPMPTSP%20Sangihe,%20saya%20sudah%20mencoba%20simulasi%20investasi%20{{ urlencode($potensi->nama) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill w-100 text-nowrap">
                                        <i class="bi bi-whatsapp me-1"></i> Konsultasi Hasil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Galeri Foto / Media -->
            @if(!empty($potensi->galeri_foto) || $potensi->foto_utama)
                <div class="info-section">
                    <h3 class="info-section-title">
                        <i class="bi bi-images text-success"></i> Dokumentasi Lokasi
                    </h3>
                    <div class="row g-3">
                        @if($potensi->foto_utama)
                            <div class="col-md-6">
                                <div class="position-relative overflow-hidden rounded-3 shadow-sm border bg-dark" style="height: 240px;">
                                    <img src="{{ asset('storage/' . $potensi->foto_utama) }}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Foto Utama">
                                    <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white px-2 py-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Foto Utama
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if(!empty($potensi->galeri_foto) && is_array($potensi->galeri_foto))
                            @foreach($potensi->galeri_foto as $idx => $foto)
                                <div class="col-md-6">
                                    <div class="position-relative overflow-hidden rounded-3 shadow-sm border bg-dark" style="height: 240px;">
                                        <img src="{{ asset('storage/' . $foto) }}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Galeri {{ $idx + 1 }}">
                                        <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-image me-1"></i> Galeri #{{ $idx + 1 }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            <!-- Dokumen Lampiran PDF / Prospektus yang Diunggah -->
            @if(!empty($potensi->pdfs) && is_array($potensi->pdfs) && count($potensi->pdfs) > 0)
                <div class="info-section">
                    <h3 class="info-section-title">
                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Dokumen Prospektus & Berkas Lampiran PDF
                    </h3>
                    <p class="text-muted small mb-3">
                        Berkas dokumen resmi, laporan prospektus investasi, atau dokumen kelayakan yang telah dilampirkan:
                    </p>
                    <div class="d-flex flex-column gap-2">
                        @foreach($potensi->pdfs as $index => $pdfPath)
                            @php
                                $fileName = basename($pdfPath);
                            @endphp
                            <div class="d-flex flex-wrap align-items-center justify-content-between p-3 border rounded-3 bg-light shadow-sm gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 text-danger fs-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">Dokumen Lampiran Prospektus #{{ $index + 1 }}</div>
                                        <small class="text-muted"><i class="bi bi-paperclip me-1"></i>{{ $fileName }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-eye-fill"></i> Buka / Baca
                                    </a>
                                    <a href="{{ asset('storage/' . $pdfPath) }}" download="{{ $fileName }}" class="btn btn-sm btn-danger px-3 py-1 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-download"></i> Unduh File
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Location & Action -->
        <div class="col-lg-4">
            <!-- Bandingkan Peluang Box -->
            <div class="info-section p-4 mb-3" style="background: linear-gradient(180deg, #ffffff 0%, #eff6ff 100%); border-color: #bfdbfe;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">Fitur Inovasi</span>
                    <span class="text-primary fw-bold small">Matriks Komparasi</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">Bandingkan Peluang Ini</h6>
                <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                    Bandingkan kelayakan finansial, ROI, infrastruktur, dan akses logistik peluang ini dengan peluang investasi lainnya secara head-to-head.
                </p>
                <a href="{{ route('potensi.compare', ['ids' => $potensi->id]) }}" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-bold btn-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Bandingkan Head-to-Head</span>
                </a>
            </div>

            <!-- Peta Lokasi -->
            <div class="info-section p-3">
                <h3 class="info-section-title" style="font-size: 0.95rem;">
                    <i class="bi bi-geo-alt-fill text-danger"></i> Lokasi Spasial
                </h3>
                <div id="location-map"></div>
                @if($potensi->latitude && $potensi->longitude)
                    <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ round($potensi->latitude, 5) }}, {{ round($potensi->longitude, 5) }}
                        </small>
                        <a href="https://www.google.com/maps?q={{ $potensi->latitude }},{{ $potensi->longitude }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                            Buka Google Maps <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Call to Action Box -->
            <div class="info-section text-center p-4" style="background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%); border-color: #bbf7d0;">
                <div class="d-inline-flex p-3 rounded-circle bg-success text-white mb-3 shadow-sm">
                    <i class="bi bi-briefcase fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Tertarik Berinvestasi?</h5>
                <p class="text-muted small mb-4">
                    DPMPTSP Kabupaten Kepulauan siap memberikan pendampingan perizinan, fasilitasi lahan, dan kemudahan berusaha.
                </p>
                <button class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm mb-2" onclick="openMinatModal()">
                    <i class="bi bi-send-fill me-1"></i> Kirim Minat Investasi
                </button>
                <a href="{{ route('potensi.pdf', $potensi->id) }}" target="_blank" class="btn btn-outline-danger w-100 py-2.5 rounded-pill fw-bold shadow-sm mb-2 d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    <span>Unduh Dokumen Prospektus (PDF)</span>
                </a>
                <a href="{{ url('/export/potensi-investasis?jenis_potensi=' . urlencode($potensi->jenis_potensi)) }}" class="btn btn-sm btn-outline-success w-100 rounded-pill py-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-file-earmark-excel-fill text-success"></i> Unduh Data Excel (.XLSX)
                </a>
            </div>

            <!-- Profil Kelurahan -->
            @if($potensi->kelurahan)
                <div class="info-section">
                    <h3 class="info-section-title" style="font-size: 0.95rem;">
                        <i class="bi bi-building text-secondary"></i> Profil {{ $potensi->kelurahan->nama }}
                    </h3>
                    <ul class="list-unstyled mb-0 small">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Kecamatan:</span>
                            <span class="fw-semibold">{{ $potensi->kelurahan->kecamatan ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Luas Wilayah:</span>
                            <span class="fw-semibold">{{ $potensi->kelurahan->luas_wilayah ? number_format((float) $potensi->kelurahan->luas_wilayah, 2, ',', '.') . ' Ha' : '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Jumlah Penduduk:</span>
                            <span class="fw-semibold">{{ $potensi->kelurahan->jumlah_penduduk ? number_format($potensi->kelurahan->jumlah_penduduk) . ' Jiwa' : '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Tenaga Kerja Siap:</span>
                            <span class="fw-semibold">{{ $potensi->kelurahan->tenaga_kerja_tersedia ? number_format($potensi->kelurahan->tenaga_kerja_tersedia) . ' Orang' : '-' }}</span>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

@auth
<!-- Modal Form Minat Investor Standar BKPM / DPMPTSP (Khusus Investor Terautentikasi) -->
<div class="modal fade" id="minatModal" tabindex="-1" aria-labelledby="minatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg text-white" style="background: #1e293b;">
            <div class="modal-header border-0 rounded-top-4 py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-circle bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-briefcase-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="minatModalLabel">Pernyataan Minat Investasi Resmi</h5>
                        <small class="text-white-50" style="font-size: 0.75rem;">Letter of Intent (LoI) / Fasilitasi Penanaman Modal DPMPTSP Kabupaten Kepulauan Sangihe</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="minatForm" enctype="multipart/form-data">
                    <input type="hidden" name="potensi_investasi_id" value="{{ $potensi->id }}">
                    <input type="hidden" name="kelurahan_id" value="{{ $potensi->kelurahan_id }}">

                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-3 d-flex align-items-center justify-content-between" style="background: rgba(14, 165, 233, 0.15); border-color: rgba(14, 165, 233, 0.3); color: #7dd3fc;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span>Peluang Sasaran: <strong>{{ $potensi->nama }}</strong> ({{ $potensi->jenis_potensi }})</span>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1">
                            <i class="bi bi-check-circle-fill me-1"></i> Akun: {{ auth()->user()->name }}
                        </span>
                    </div>

                    <!-- 1. Identitas Badan Usaha -->
                    <h6 class="fw-bold text-info border-bottom border-secondary pb-1 mb-3">
                        <i class="bi bi-person-lines-fill me-1"></i> 1. Identitas Badan Usaha & Pemohon
                    </h6>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">Nama Lengkap PIC / Pemohon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="nama" value="{{ auth()->user()->name }}" required placeholder="Contoh: Budi Santoso, M.B.A.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">Jabatan di Perusahaan</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="jabatan" value="{{ auth()->user()->jabatan }}" placeholder="Contoh: Direktur Utama / Kepala Investasi">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">Nama Perusahaan / Entitas</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="perusahaan" value="{{ auth()->user()->perusahaan }}" placeholder="PT / CV / BUMN / Konsorsium / Perorangan">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-white-50">Klasifikasi Modal</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="jenis_investor">
                                <option value="pmdn" selected>PMDN (Modal Domestik)</option>
                                <option value="pma">PMA (Modal Asing)</option>
                                <option value="perorangan">Perorangan / Mandiri</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-white-50">Kota / Negara Asal</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="asal_negara" placeholder="Contoh: Jakarta / Singapura">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">NIB / NPWP Badan (Opsional)</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="nib" placeholder="Nomor Induk Berusaha">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">Email Korespondensi <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm bg-dark border-secondary text-white" name="email" value="{{ auth()->user()->email }}" required placeholder="investor@perusahaan.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">No. WhatsApp / Telepon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-sm bg-dark border-secondary text-white" name="telepon" value="{{ auth()->user()->telepon }}" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- 2. Rencana & Kapasitas Investasi -->
                    <h6 class="fw-bold text-info border-bottom border-secondary pb-1 mb-3 mt-4">
                        <i class="bi bi-cash-coin me-1"></i> 2. Rencana & Kapasitas Penanaman Modal
                    </h6>

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">Perkiraan Alokasi Modal</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="rentang_modal">
                                <option value="< Rp 1 Miliar">< Rp 1 Miliar (Usaha Mikro/Kecil)</option>
                                <option value="Rp 1 - 5 Miliar" selected>Rp 1 - 5 Miliar (Menengah)</option>
                                <option value="Rp 5 - 25 Miliar">Rp 5 - 25 Miliar (Besar)</option>
                                <option value="> Rp 25 Miliar">> Rp 25 Miliar (Strategis)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">Model Kerja Sama Diminati</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="model_kerjasama">
                                <option value="kpbu">KPBU / PPP (Pemerintah-Swasta)</option>
                                <option value="sewa_lahan" selected>Sewa Lahan / Aset Pemda (BOT/BTO)</option>
                                <option value="joint_venture">Joint Venture / Usaha Bersama</option>
                                <option value="mandiri_100">Investasi Mandiri 100%</option>
                                <option value="lainnya">Lainnya / Diskusi</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-white-50">Target Realisasi</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="timeline_realisasi">
                                <option value="segera" selected>Segera (< 6 Bulan)</option>
                                <option value="6_12_bulan">Jangka Pendek (6 - 12 Bulan)</option>
                                <option value="studi_kelayakan">Tahap Studi Kelayakan</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Fasilitasi Pemkab & Dokumen Pendukung -->
                    <h6 class="fw-bold text-info border-bottom border-secondary pb-1 mb-3 mt-4">
                        <i class="bi bi-patch-check me-1"></i> 3. Kebutuhan Fasilitasi Pemda & Berkas Lampiran
                    </h6>

                    <label class="form-label small fw-bold text-white-50 mb-2">Dukungan yang Diharapkan dari DPMPTSP:</label>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="form-check small">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="kebutuhan_fasilitasi[]" value="perizinan_oss" id="fas1" checked>
                                <label class="form-check-label text-white-50" for="fas1">Pendampingan Perizinan OSS-RBA & AMDAL</label>
                            </div>
                            <div class="form-check small mt-1">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="kebutuhan_fasilitasi[]" value="fasilitasi_lahan" id="fas2" checked>
                                <label class="form-check-label text-white-50" for="fas2">Kepastian Hak Atas Lahan & Tata Ruang</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check small">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="kebutuhan_fasilitasi[]" value="audiensi_pemkab" id="fas3" checked>
                                <label class="form-check-label text-white-50" for="fas3">Jadwal Audiensi Teknis dengan Tim Pemkab</label>
                            </div>
                            <div class="form-check small mt-1">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="kebutuhan_fasilitasi[]" value="insentif_daerah" id="fas4">
                                <label class="form-check-label text-white-50" for="fas4">Kemudahan Pajak / Insentif Daerah</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white-50">Catatan Khusus / Gambaran Singkat Rencana Usaha</label>
                        <textarea class="form-control form-control-sm bg-dark border-secondary text-white" name="pesan" rows="2" placeholder="Sampaikan kebutuhan spesifikasi lahan, sarana air/listrik, atau gambaran proyek yang direncanakan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white-50">Unggah Dokumen LoI / Profil Perusahaan (PDF, Opsional)</label>
                        <input type="file" class="form-control form-control-sm bg-dark border-secondary text-white" name="dokumen_loi" accept=".pdf,application/pdf">
                        <small class="text-white-50" style="font-size: 0.72rem;">Format PDF maksimal 10MB.</small>
                    </div>

                    <div id="minatAlert" class="d-none"></div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
                        <a href="https://wa.me/6281234567890?text=Halo%20DPMPTSP%20Sangihe,%20saya%20ingin%20berkonsultasi%20mengenai%20peluang%20investasi%20{{ urlencode($potensi->nama) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="bi bi-whatsapp me-1"></i> Konsultasi Cepat WhatsApp
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-info rounded-pill px-4 fw-bold text-dark" id="submitBtn">
                                <span id="btnText"><i class="bi bi-send-fill me-1"></i> Kirim Minat Resmi</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<!-- Dialog Wajib Masuk / Registrasi Akun Investor Terlebih Dahulu (Untuk Tamu / Belum Login) -->
<div class="modal fade" id="minatModal" tabindex="-1" aria-labelledby="minatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-white" style="background: #1e293b;">
            <div class="modal-header border-0 rounded-top-4 py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-warning fs-4"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" id="minatModalLabel">Akses Portal Penanaman Modal</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;">DPMPTSP Kabupaten Kepulauan Sangihe</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-10 text-info mb-3">
                    <i class="bi bi-person-badge fs-1"></i>
                </div>
                <h5 class="fw-bold text-white mb-2">Masuk atau Buat Akun Investor Terlebih Dahulu</h5>
                <p class="text-white-50 small mb-4">
                    Untuk menyampaikan <strong>Pernyataan Minat Investasi Resmi (Letter of Intent)</strong> pada peluang <strong class="text-white">{{ $potensi->nama }}</strong> dan memantau status verifikasi, jadwal audiensi, serta rekomendasi teknis di Dashboard Anda, silakan masuk atau daftarkan akun entitas Anda terlebih dahulu.
                </p>

                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('investor.login', ['redirect' => url()->current()]) }}" class="btn btn-info py-2 rounded-pill fw-bold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                        <span>Masuk ke Portal Investor</span>
                    </a>
                    <a href="{{ route('investor.register', ['redirect' => url()->current()]) }}" class="btn btn-outline-light py-2 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-person-plus-fill text-info fs-5"></i>
                        <span>Daftar Akun Baru (Gratis & Cepat)</span>
                    </a>
                </div>

                <div class="pt-3 border-top border-secondary">
                    <small class="text-white-50 d-block mb-2">Ingin berkonsultasi santai tanpa membuat akun?</small>
                    <a href="https://wa.me/6281234567890?text=Halo%20DPMPTSP%20Sangihe,%20saya%20ingin%20berkonsultasi%20mengenai%20peluang%20investasi%20{{ urlencode($potensi->nama) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-whatsapp"></i> Hubungi WhatsApp DPMPTSP Sangihe
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const modalEl = document.getElementById('minatModal');
    const modalInstance = modalEl ? new bootstrap.Modal(modalEl) : null;

    function openMinatModal() {
        const alertBox = document.getElementById('minatAlert');
        if (alertBox) alertBox.className = 'd-none';
        if (modalInstance) modalInstance.show();
    }

    // --- KALKULATOR SIMULASI INVESTASI & ROI ---
    const defaultModalVal = {{ $modalDefault }};
    const defaultRoiVal = {{ $roiDefault }};
    let simChartInstance = null;

    function formatRupiah(num) {
        if (num >= 1000000000) {
            return 'Rp ' + (num / 1000000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 2 }) + ' Miliar';
        } else if (num >= 1000000) {
            return 'Rp ' + (num / 1000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' Juta';
        } else {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }
    }

    function setModalValue(val, btn) {
        const slider = document.getElementById('simModalRange');
        if (slider) {
            slider.value = val;
            runSimulation();
        }
        if (btn) {
            document.querySelectorAll('.sim-chip-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    }

    function resetSimulation() {
        const modalSlider = document.getElementById('simModalRange');
        const roiSlider = document.getElementById('simRoiRange');
        const defaultTenor = document.getElementById('tenor5');
        const defaultModel = document.getElementById('simModelKerjasama');

        if (modalSlider) modalSlider.value = defaultModalVal;
        if (roiSlider) roiSlider.value = defaultRoiVal;
        if (defaultTenor) defaultTenor.checked = true;
        if (defaultModel) defaultModel.value = 'sewa_lahan';

        document.querySelectorAll('.sim-chip-btn').forEach(b => b.classList.remove('active'));
        const chipData = document.getElementById('chipPresetData');
        if (chipData) chipData.classList.add('active');

        runSimulation();
    }

    function runSimulation() {
        const modalSlider = document.getElementById('simModalRange');
        const roiSlider = document.getElementById('simRoiRange');
        const tenorChecked = document.querySelector('input[name="simTenor"]:checked');

        if (!modalSlider || !roiSlider) return;

        const modal = parseFloat(modalSlider.value) || 0;
        const roi = parseFloat(roiSlider.value) || 0;
        const tenor = tenorChecked ? parseInt(tenorChecked.value) : 5;

        // Display Inputs
        const simModalDisplay = document.getElementById('simModalDisplay');
        const simRoiDisplay = document.getElementById('simRoiDisplay');
        const simRoiBadge = document.getElementById('simRoiBadge');

        if (simModalDisplay) simModalDisplay.innerText = formatRupiah(modal);
        if (simRoiDisplay) simRoiDisplay.innerText = roi.toFixed(1).replace('.', ',') + '%';

        // ROI Risk Category
        if (simRoiBadge) {
            if (roi <= 10) {
                simRoiBadge.className = 'badge bg-secondary-subtle text-secondary border rounded-pill';
                simRoiBadge.innerText = 'Konservatif';
            } else if (roi <= 20) {
                simRoiBadge.className = 'badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill';
                simRoiBadge.innerText = 'Moderat (Ideal)';
            } else if (roi <= 30) {
                simRoiBadge.className = 'badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill';
                simRoiBadge.innerText = 'Prospektif';
            } else {
                simRoiBadge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill';
                simRoiBadge.innerText = 'Agresif (Tinggi)';
            }
        }

        // Calculations
        const annualProfit = modal * (roi / 100);
        const paybackYears = roi > 0 ? (100 / roi) : 0;
        const totalProfit = annualProfit * tenor;
        const naker = Math.max(3, Math.round(modal / 65000000));

        // Display Outputs
        const simLabaTahunanDisplay = document.getElementById('simLabaTahunanDisplay');
        const simBepDisplay = document.getElementById('simBepDisplay');
        const simBepStatusBadge = document.getElementById('simBepStatusBadge');
        const simTotalProfitDisplay = document.getElementById('simTotalProfitDisplay');
        const simTotalProfitSubtext = document.getElementById('simTotalProfitSubtext');
        const simNakerDisplay = document.getElementById('simNakerDisplay');
        const simChartCaption = document.getElementById('simChartCaption');

        if (simLabaTahunanDisplay) simLabaTahunanDisplay.innerText = formatRupiah(annualProfit);

        if (simBepDisplay) {
            simBepDisplay.innerText = paybackYears > 0 ? paybackYears.toFixed(1).replace('.', ',') + ' Thn' : 'N/A';
        }

        if (simBepStatusBadge) {
            if (paybackYears <= 3) {
                simBepStatusBadge.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-lightning-charge-fill me-1"></i>Sangat Cepat</span>';
            } else if (paybackYears <= 6) {
                simBepStatusBadge.innerHTML = '<span class="text-primary fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Standar Sehat</span>';
            } else if (paybackYears <= 10) {
                simBepStatusBadge.innerHTML = '<span class="text-warning fw-bold"><i class="bi bi-clock-history me-1"></i>Jangka Menengah</span>';
            } else {
                simBepStatusBadge.innerHTML = '<span class="text-secondary fw-semibold">Jangka Panjang</span>';
            }
        }

        if (simTotalProfitDisplay) simTotalProfitDisplay.innerText = formatRupiah(totalProfit);
        if (simTotalProfitSubtext) simTotalProfitSubtext.innerText = 'Akumulasi tenor ' + tenor + ' tahun';
        if (simNakerDisplay) simNakerDisplay.innerText = '± ' + naker + ' Pekerja';
        if (simChartCaption) simChartCaption.innerText = 'Horizon ' + tenor + ' Tahun (Arus Kas Kumulatif)';

        // Render / Update Chart
        renderSimChart(modal, annualProfit, tenor);
    }

    function renderSimChart(modal, annualProfit, tenor) {
        const canvas = document.getElementById('simCashFlowChart');
        if (!canvas) return;

        const labels = ['T0 (CAPEX)'];
        const data = [-modal];
        const bgColors = ['rgba(239, 68, 68, 0.75)'];

        for (let t = 1; t <= tenor; t++) {
            labels.push('T' + t);
            const cumCash = -modal + (t * annualProfit);
            data.push(cumCash);
            if (cumCash >= 0) {
                bgColors.push('rgba(16, 185, 129, 0.8)');
            } else {
                bgColors.push('rgba(245, 158, 11, 0.75)');
            }
        }

        if (window.Chart) {
            if (simChartInstance) {
                simChartInstance.data.labels = labels;
                simChartInstance.data.datasets[0].data = data;
                simChartInstance.data.datasets[0].backgroundColor = bgColors;
                simChartInstance.update();
            } else {
                const ctx = canvas.getContext('2d');
                simChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Arus Kas Kumulatif (Rp)',
                            data: data,
                            backgroundColor: bgColors,
                            borderRadius: 6,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = context.parsed.y;
                                        return (val < 0 ? 'Defisit: -' : 'Surplus: +') + formatRupiah(Math.abs(val));
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                grid: { color: 'rgba(0,0,0,0.06)' },
                                ticks: {
                                    callback: function(value) {
                                        if (Math.abs(value) >= 1000000000) return (value / 1000000000).toFixed(1) + 'M';
                                        if (Math.abs(value) >= 1000000) return (value / 1000000).toFixed(0) + 'Jt';
                                        return value;
                                    },
                                    font: { size: 9 }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: '600' } }
                            }
                        }
                    }
                });
            }
        } else {
            drawFallbackCanvas(canvas, labels, data);
        }
    }

    function drawFallbackCanvas(canvas, labels, data) {
        const ctx = canvas.getContext('2d');
        const w = canvas.width = canvas.parentElement.clientWidth;
        const h = canvas.height = 180;
        ctx.clearRect(0, 0, w, h);

        const maxVal = Math.max(...data.map(Math.abs));
        const zeroY = h / 2;
        ctx.strokeStyle = '#cbd5e1';
        ctx.beginPath();
        ctx.moveTo(30, zeroY);
        ctx.lineTo(w - 10, zeroY);
        ctx.stroke();

        const barWidth = (w - 60) / data.length;
        data.forEach((val, i) => {
            const x = 40 + (i * barWidth);
            const barH = (val / maxVal) * (h / 2 - 20);
            ctx.fillStyle = val >= 0 ? '#10b981' : '#ef4444';
            ctx.fillRect(x, zeroY, barWidth - 8, -barH);
            ctx.fillStyle = '#64748b';
            ctx.font = '10px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(labels[i], x + (barWidth - 8)/2, h - 5);
        });
    }

    function applySimulationToLoI() {
        const modalSlider = document.getElementById('simModalRange');
        const roiSlider = document.getElementById('simRoiRange');
        const modelSelect = document.getElementById('simModelKerjasama');
        const tenorChecked = document.querySelector('input[name="simTenor"]:checked');

        const modalVal = parseFloat(modalSlider?.value) || 0;
        const roiVal = parseFloat(roiSlider?.value) || 0;
        const modelVal = modelSelect ? modelSelect.value : 'sewa_lahan';
        const modelText = modelSelect ? modelSelect.options[modelSelect.selectedIndex]?.text : '';
        const tenorVal = tenorChecked ? tenorChecked.value : '5';

        // Map modal to bracket
        let rentangOption = 'Rp 1 - 5 Miliar';
        if (modalVal < 1000000000) {
            rentangOption = '< Rp 1 Miliar';
        } else if (modalVal <= 5000000000) {
            rentangOption = 'Rp 1 - 5 Miliar';
        } else if (modalVal <= 25000000000) {
            rentangOption = 'Rp 5 - 25 Miliar';
        } else {
            rentangOption = '> Rp 25 Miliar';
        }

        const rentangModalSelect = document.querySelector('select[name="rentang_modal"]');
        if (rentangModalSelect) {
            for (let opt of rentangModalSelect.options) {
                if (opt.value === rentangOption) {
                    opt.selected = true;
                    break;
                }
            }
        }

        const formModelSelect = document.querySelector('select[name="model_kerjasama"]');
        if (formModelSelect) {
            for (let opt of formModelSelect.options) {
                if (opt.value === modelVal) {
                    opt.selected = true;
                    break;
                }
            }
        }

        const pesanTextarea = document.querySelector('textarea[name="pesan"]');
        if (pesanTextarea) {
            const bepText = document.getElementById('simBepDisplay')?.innerText || '-';
            const labaTahunan = document.getElementById('simLabaTahunanDisplay')?.innerText || '-';
            const totalProfit = document.getElementById('simTotalProfitDisplay')?.innerText || '-';

            pesanTextarea.value = `[Hasil Simulasi Kalkulator Investasi & ROI]:\n` +
                `• Alokasi Rencana Modal (CAPEX): ${formatRupiah(modalVal)}\n` +
                `• Target Asumsi ROI: ${roiVal.toFixed(1)}% / tahun (Est. Laba Bersih: ${labaTahunan})\n` +
                `• Horizon Tenor: ${tenorVal} Tahun (Total Estimasi Laba: ${totalProfit})\n` +
                `• Payback Period (BEP): ${bepText}\n` +
                `• Model Kemitraan: ${modelText}\n\n` +
                `Kami berminat menjadwalkan audiensi teknis serta konsultasi perizinan terpadu bersama tim DPMPTSP Kabupaten Kepulauan Sangihe.`;
        }

        openMinatModal();
    }

    function printSimulationSummary() {
        window.print();
    }

    function copyProspectusLink(btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.href);
            const origHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-6"></i> <span>Tersalin!</span>';
            setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Bind Simulation Sliders
        const modalSlider = document.getElementById('simModalRange');
        const roiSlider = document.getElementById('simRoiRange');
        const modelSelect = document.getElementById('simModelKerjasama');

        if (modalSlider) {
            modalSlider.addEventListener('input', () => {
                document.querySelectorAll('.sim-chip-btn').forEach(b => b.classList.remove('active'));
                runSimulation();
            });
        }
        if (roiSlider) {
            roiSlider.addEventListener('input', runSimulation);
        }
        if (modelSelect) {
            modelSelect.addEventListener('change', runSimulation);
        }

        // Run initial simulation
        runSimulation();

        // Initialize Mini Map
        @php
            $lat = (float) $potensi->latitude;
            $lng = (float) $potensi->longitude;
            $hasCoords = !empty($lat) && !empty($lng);
        @endphp

        const lat = {{ $hasCoords ? $lat : 3.58 }};
        const lng = {{ $hasCoords ? $lng : 125.58 }};

        const map = L.map('location-map').setView([lat, lng], 13);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, TomTom'
        }).addTo(map);

        setTimeout(() => { map.invalidateSize(); }, 300);

        @if($hasCoords)
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<strong>{{ addslashes($potensi->nama) }}</strong><br><small>{{ addslashes($potensi->kelurahan?->nama ?? '') }}</small>')
                .openPopup();
        @endif

        // Handle AJAX form submission for authenticated investor
        const minatForm = document.getElementById('minatForm');
        if (minatForm) {
            minatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');
                const alertBox = document.getElementById('minatAlert');

                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
                submitBtn.disabled = true;
                alertBox.className = 'd-none';

                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                fetch('/api/minat-investasi', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    btnText.classList.remove('d-none');
                    btnSpinner.classList.add('d-none');
                    submitBtn.disabled = false;

                    if (res.ok && data.success) {
                        alertBox.className = 'alert alert-success py-3 px-3 small rounded-3 mb-0';
                        alertBox.innerHTML = `
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <i class="bi bi-check-circle-fill me-1"></i> ${data.message || 'Pernyataan minat resmi Anda berhasil dikirim.'}
                                </div>
                                <a href="{{ route('investor.dashboard') }}" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3">
                                    <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard Saya
                                </a>
                            </div>
                        `;
                        setTimeout(() => {
                            if (modalInstance) modalInstance.hide();
                            form.reset();
                        }, 4000);
                    } else {
                        alertBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0';
                        alertBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + (data.message || 'Terjadi kesalahan pengiriman formulir.');
                    }
                })
                .catch(err => {
                    btnText.classList.remove('d-none');
                    btnSpinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    alertBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0';
                    alertBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal mengirim data. Silakan coba lagi.';
                });
            });
        }
    });
</script>
@endpush
