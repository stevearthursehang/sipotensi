@extends('layouts.app')

@section('title', 'Masuk Portal Investor - GIS Penanaman Modal Sangihe')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: #1e293b; color: #f8fafc;">
                <div class="card-header border-0 text-center pt-4 pb-2" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-10 text-info mb-3">
                        <i class="bi bi-person-badge-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-1">Masuk Portal Investor</h4>
                    <p class="text-white-50 small mb-0">Kelola dan pantau seluruh permohonan minat investasi Anda</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info py-2 px-3 small rounded-3 mb-4">
                            <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('investor.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-semibold">Alamat Email Perusahaan / Pribadi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label text-white-50 small fw-semibold">Kata Sandi (Password)</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember" checked>
                                <label class="form-check-label text-white-50 small" for="remember">
                                    Ingat sesi saya
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2.5 rounded-pill fw-bold text-dark shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top border-secondary">
                        <span class="text-white-50 small">Belum memiliki akun investor?</span>
                        <a href="{{ route('investor.register') }}" class="text-info text-decoration-none fw-bold small ms-1">
                            Daftar Akun Baru <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

