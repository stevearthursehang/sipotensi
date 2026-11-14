@extends('layouts.app')

@section('title', 'Dashboard Portal Investor - GIS Penanaman Modal Sangihe')

@section('content')
<div class="container py-4 py-lg-5" style="min-height: 85vh;">
    <!-- Welcome Header -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info">
                            <i class="bi bi-person-workspace fs-2"></i>
                        </div>
                        <div>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.75rem;">
                                PORTAL RESMI PENANAMAN MODAL INVESTOR
                            </span>
                            <h2 class="fw-bold mb-0 mt-1">{{ $user->name }}</h2>
                        </div>
                    </div>
                    <p class="text-white-50 mb-0 ps-lg-5 ms-lg-2">
                        @if($user->perusahaan)
                            <strong class="text-white">{{ $user->perusahaan }}</strong> ({{ $user->jabatan ?: 'Investor' }}) &bull;
                        @endif
                        <i class="bi bi-envelope me-1"></i> {{ $user->email }} &bull;
                        <i class="bi bi-whatsapp me-1 text-success"></i> {{ $user->telepon ?: '-' }}
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0 d-flex flex-wrap gap-2 justify-content-lg-end">
                    <button class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Profil Entitas</span>
                    </button>
                    <a href="{{ route('map.index') }}" class="btn btn-info rounded-pill px-4 py-2 fw-bold text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-compass"></i>
                        <span>Jelajahi Peta Peluang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 py-3 px-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Metric Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background: #1e293b; color: white;">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-white-50 small fw-semibold">Total Minat Diajukan</span>
                        <div class="p-2 rounded-3 bg-secondary bg-opacity-25 text-white">
                            <i class="bi bi-send-check fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-white">{{ $stats['total'] }}</h3>
                    <small class="text-white-50" style="font-size: 0.72rem;">Permohonan penanaman modal</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background: #1e293b; color: white;">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-white-50 small fw-semibold">Menunggu Review</span>
                        <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['diajukan'] }}</h3>
                    <small class="text-white-50" style="font-size: 0.72rem;">Verifikasi awal kelengkapan</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background: #1e293b; color: white;">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-white-50 small fw-semibold">Sedang Ditinjau Tim</span>
                        <div class="p-2 rounded-3 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-search fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-info">{{ $stats['sedang_ditinjau'] }}</h3>
                    <small class="text-white-50" style="font-size: 0.72rem;">Kajian tata ruang & teknis dinas</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm h-100" style="background: #1e293b; color: white;">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-white-50 small fw-semibold">Audiensi / Disetujui</span>
                        <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-award fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['jadwal_audiensi'] + $stats['disetujui'] }}</h3>
                    <small class="text-white-50" style="font-size: 0.72rem;">Siap ke tahap MoU / Perizinan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Minat Investasi -->
    <div class="card border-0 rounded-4 shadow-sm mb-5 overflow-hidden" style="background: #1e293b; color: white;">
        <div class="card-header border-0 bg-transparent py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-collection-fill text-info fs-5"></i>
                <h5 class="fw-bold text-white mb-0">Riwayat & Status Minat Penanaman Modal Saya</h5>
            </div>
            <span class="badge bg-secondary rounded-pill px-3 py-1">{{ $inquiries->count() }} Data</span>
        </div>

        <div class="card-body p-0">
            @if($inquiries->isEmpty())
                <div class="text-center py-5 px-3">
                    <div class="d-inline-flex p-4 rounded-circle bg-secondary bg-opacity-25 text-white-50 mb-3">
                        <i class="bi bi-inbox fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-2">Belum Ada Pengajuan Minat Investasi</h5>
                    <p class="text-white-50 small mb-4" style="max-width: 480px; margin: 0 auto;">
                        Anda belum mengirimkan pernyataan minat penanaman modal. Silakan jelajahi peta potensi investasi di Kabupaten Kepulauan Sangihe dan klik "Daftar Minat Investasi".
                    </p>
                    <a href="{{ route('map.index') }}" class="btn btn-info rounded-pill px-4 py-2 fw-bold text-dark">
                        <i class="bi bi-map me-1"></i> Buka Peta Interaktif Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="border-color: #334155;">
                        <thead style="background: #0f172a; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="py-3 px-4">Peluang Investasi</th>
                                <th class="py-3">Klasifikasi & Rencana Modal</th>
                                <th class="py-3">Status Verifikasi DPMPTSP</th>
                                <th class="py-3">Tanggal Pengajuan</th>
                                <th class="py-3 px-4 text-end">Aksi & Berkas</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.88rem;">
                            @foreach($inquiries as $inquiry)
                                @php
                                    $potensi = $inquiry->potensiInvestasi;
                                    $statusBadge = match($inquiry->status) {
                                        'diajukan' => ['bg' => 'warning text-dark', 'label' => 'Diajukan (Menunggu Review)', 'icon' => 'bi-hourglass-split'],
                                        'sedang_ditinjau' => ['bg' => 'info text-dark', 'label' => 'Sedang Ditinjau Tim DPMPTSP', 'icon' => 'bi-search'],
                                        'jadwal_audiensi' => ['bg' => 'primary text-white', 'label' => 'Dijadwalkan Audiensi', 'icon' => 'bi-calendar-event'],
                                        'disetujui' => ['bg' => 'success text-white', 'label' => 'Disetujui / Rekomendasi Terbit', 'icon' => 'bi-check-circle-fill'],
                                        'ditolak' => ['bg' => 'danger text-white', 'label' => 'Belum Memenuhi Syarat', 'icon' => 'bi-x-circle-fill'],
                                        default => ['bg' => 'secondary text-white', 'label' => ucfirst($inquiry->status ?? 'Diajukan'), 'icon' => 'bi-circle'],
                                    };
                                @endphp
                                <tr>
                                    <td class="py-3 px-4">
                                        @if($potensi)
                                            <a href="{{ route('potensi.detail', $potensi->id) }}" class="fw-bold text-info text-decoration-none fs-6 d-block mb-1">
                                                {{ $potensi->nama }} <i class="bi bi-box-arrow-up-right small"></i>
                                            </a>
                                            <small class="text-white-50 d-block">
                                                <i class="bi bi-geo-alt text-danger me-1"></i>
                                                {{ $potensi->kelurahan ? $potensi->kelurahan->nama . ', Kec. ' . ($potensi->kelurahan->kecamatan ?? '-') : 'Kab. Kepulauan Sangihe' }}
                                            </small>
                                            <span class="badge bg-secondary bg-opacity-50 text-white rounded-pill mt-1" style="font-size: 0.7rem;">
                                                Sektor: {{ $potensi->jenis_potensi ?? 'Pariwisata' }}
                                            </span>
                                        @else
                                            <span class="fw-bold text-white">Potensi Investasi #{{ $inquiry->potensi_investasi_id }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold text-white">
                                            {{ $inquiry->rentang_modal ?: ($inquiry->nilai_komitmen ? 'Rp ' . number_format($inquiry->nilai_komitmen, 0, ',', '.') : 'Konsultasi Terbuka') }}
                                        </div>
                                        <small class="text-white-50 d-block">
                                            Modal: <strong class="text-uppercase text-info">{{ $inquiry->jenis_investor ?? 'PMDN' }}</strong>
                                            @if($inquiry->model_kerjasama)
                                                &bull; {{ str_replace('_', ' ', strtoupper($inquiry->model_kerjasama)) }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $statusBadge['bg'] }} rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 0.78rem;">
                                            <i class="bi {{ $statusBadge['icon'] }}"></i>
                                            <span>{{ $statusBadge['label'] }}</span>
                                        </span>

                                        @if($inquiry->catatan_admin)
                                            <div class="mt-2 p-2 rounded-3 border border-info border-opacity-25 bg-info bg-opacity-10" style="max-width: 320px; font-size: 0.78rem;">
                                                <strong class="text-info d-block mb-0.5"><i class="bi bi-chat-left-dots-fill me-1"></i> Catatan Resmi Dinas:</strong>
                                                <span class="text-white-50">{{ $inquiry->catatan_admin }}</span>
                                                @if($inquiry->tanggal_tindak_lanjut)
                                                    <div class="text-warning mt-1 fw-semibold">
                                                        <i class="bi bi-clock-history me-1"></i> Jadwal: {{ $inquiry->tanggal_tindak_lanjut->format('d M Y H:i') }} WITA
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-white-50 small">
                                        {{ $inquiry->created_at->format('d M Y') }}<br>
                                        <span style="font-size: 0.72rem;">{{ $inquiry->created_at->format('H:i') }} WITA</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <div class="d-inline-flex gap-1">
                                            @if($inquiry->dokumen_loi)
                                                <a href="{{ asset('storage/' . $inquiry->dokumen_loi) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="Buka Dokumen LoI / Lampiran Saya">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> LoI
                                                </a>
                                            @endif
                                            @if($potensi)
                                                <a href="{{ route('potensi.pdf', $potensi->id) }}" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-2.5 py-1" title="Unduh Lembar Prospektus Resmi">
                                                    <i class="bi bi-download"></i> Prospektus
                                                </a>
                                            @endif
                                            <a href="https://wa.me/6281234567890?text=Halo%20DPMPTSP%20Sangihe,%20saya%20ingin%20menanyakan%20status%20minat%20investasi%20untuk%20{{ urlencode($potensi?->nama ?? 'Peluang Investasi') }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-2.5 py-1" title="Tanya Tim DPMPTSP via WhatsApp">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Rekomendasi Peluang Investasi Lainnya -->
    <div class="row align-items-center mb-3">
        <div class="col">
            <h5 class="fw-bold text-white mb-0">
                <i class="bi bi-stars text-warning me-1"></i> Peluang Penanaman Modal Unggulan Lainnya
            </h5>
            <small class="text-white-50">Jelajahi sektor perikanan, pariwisata bahari, pertanian, dan energi di Kepulauan Sangihe</small>
        </div>
        <div class="col-auto">
            <a href="{{ route('map.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                Lihat Seluruh Peta <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach($rekomendasiPotensi as $item)
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: #1e293b; color: white;">
                    <div class="position-relative" style="height: 160px; background: #0f172a;">
                        @if($item->foto_utama)
                            <img src="{{ asset('storage/' . $item->foto_utama) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $item->nama }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 text-info px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                            {{ $item->jenis_potensi }}
                        </span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="fw-bold text-white mb-1">{{ $item->nama }}</h6>
                            <small class="text-white-50 d-block mb-2">
                                <i class="bi bi-geo-alt text-danger me-1"></i>
                                {{ $item->kelurahan ? $item->kelurahan->nama . ', Kec. ' . ($item->kelurahan->kecamatan ?? '-') : '-' }}
                            </small>
                            <div class="fw-bold text-success mb-3" style="font-size: 0.9rem;">
                                Estimasi: Rp {{ number_format($item->nilai_estimasi_investasi, 0, ',', '.') }}
                            </div>
                        </div>
                        <a href="{{ route('potensi.detail', $item->id) }}" class="btn btn-sm btn-outline-info rounded-pill w-100 py-1.5 fw-semibold">
                            Kaji Prospektus Lengkap <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Edit Profil Investor -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg text-white" style="background: #1e293b;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-white" id="profileModalLabel">
                    <i class="bi bi-pencil-square text-info me-1"></i> Perbarui Data Entitas & Kontak
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('investor.profile.update') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-semibold">Nama Lengkap PIC *</label>
                        <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-semibold">Nama Perusahaan / Entitas</label>
                        <input type="text" name="perusahaan" class="form-control bg-dark border-secondary text-white" value="{{ old('perusahaan', $user->perusahaan) }}" placeholder="PT. / CV. / Konsorsium">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-white-50 small fw-semibold">Jabatan di Perusahaan</label>
                            <input type="text" name="jabatan" class="form-control bg-dark border-secondary text-white" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Direktur / PIC">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small fw-semibold">WhatsApp / Telepon *</label>
                            <input type="text" name="telepon" class="form-control bg-dark border-secondary text-white" value="{{ old('telepon', $user->telepon) }}" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-white-50 small fw-semibold">Email Korespondensi (Tetap)</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white-50" value="{{ $user->email }}" readonly disabled>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info rounded-pill px-4 fw-bold text-dark">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

