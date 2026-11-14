@extends('layouts.app')

@section('title', 'Pendaftaran Akun Investor - GIS Penanaman Modal Sangihe')

@section('content')
<div class="container py-5" style="min-height: 85vh;">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #1e293b; color: #f8fafc;">
                <div class="card-header border-0 text-center pt-4 pb-2" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-10 text-info mb-2">
                        <i class="bi bi-briefcase-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-1">Registrasi Akun Investor</h4>
                    <p class="text-white-50 small mb-0">Daftarkan entitas Anda untuk mengajukan minat dan mengakses fasilitas penanaman modal DPMPTSP</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('investor.register') }}">
                        @csrf

                        <h6 class="fw-bold text-info border-bottom border-secondary pb-2 mb-3">
                            <i class="bi bi-person-circle me-1"></i> Data Kontak & Badan Usaha
                        </h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Nama Lengkap PIC / Pemohon *</label>
                                <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, M.B.A." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Jabatan di Perusahaan</label>
                                <input type="text" name="jabatan" class="form-control bg-dark border-secondary text-white" value="{{ old('jabatan') }}" placeholder="Contoh: Direktur Utama / Kepala Investasi">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Nama Perusahaan / Entitas</label>
                                <input type="text" name="perusahaan" class="form-control bg-dark border-secondary text-white" value="{{ old('perusahaan') }}" placeholder="Contoh: PT. Bahari Sejahtera Nusantara">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Nomor WhatsApp / Telepon Resmi *</label>
                                <input type="text" name="telepon" class="form-control bg-dark border-secondary text-white" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <h6 class="fw-bold text-info border-bottom border-secondary pb-2 mb-3 mt-4">
                            <i class="bi bi-shield-lock me-1"></i> Kredensial Akun Portal
                        </h6>

                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-semibold">Email Korespondensi (Username) *</label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ old('email') }}" placeholder="investor@perusahaan.com" required>
                            <small class="text-white-50" style="font-size: 0.75rem;">Seluruh konfirmasi dan dokumen tindak lanjut akan dikirim ke email ini.</small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Kata Sandi (Password) *</label>
                                <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50 small fw-semibold">Ulangi Kata Sandi *</label>
                                <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" placeholder="Ketik ulang password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2.5 rounded-pill fw-bold text-dark shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Selesaikan Registrasi & Masuk Dashboard
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top border-secondary">
                        <span class="text-white-50 small">Sudah memiliki akun?</span>
                        <a href="{{ route('investor.login') }}" class="text-info text-decoration-none fw-bold small ms-1">
                            Masuk ke Portal Investor <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

