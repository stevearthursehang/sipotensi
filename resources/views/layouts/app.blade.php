<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'GIS Peta Potensi Investasi - Kab. Kepulauan')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Leaflet CSS & JS (Cloudflare cdnjs - global fast CDN with local fallback) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" onerror="this.onerror=null;this.href='/vendor/leaflet/leaflet.css';" />
    <script id="leafletScript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" onerror="this.onerror=null;this.src='/vendor/leaflet/leaflet.js';"></script>

    <style>
        :root {
            --gis-primary: #0a0f1d;
            --gis-secondary: #0f172a;
            --gis-card-dark: #131c31;
            --gis-accent: #2563eb;
            --gis-accent-hover: #1d4ed8;
            --gis-gold: #f59e0b;
            --gis-gold-glow: rgba(245, 158, 11, 0.35);
            --gis-emerald: #10b981;
            --gis-cyan: #06b6d4;
            --gis-border: #e2e8f0;
            --gis-border-dark: rgba(255, 255, 255, 0.1);
            --gis-text: #0f172a;
            --gis-text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--gis-text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        /* Top Iridescent Luxury Line */
        .top-luxury-line {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #f59e0b, #3b82f6, #06b6d4, #10b981, #f59e0b);
            background-size: 300% 100%;
            animation: luxuryGradient 8s ease infinite;
            position: sticky;
            top: 0;
            z-index: 1060;
        }
        @keyframes luxuryGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Custom Slim Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .navbar-gis {
            background: rgba(10, 15, 29, 0.94) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            z-index: 1040;
        }

        .navbar-gis .navbar-brand {
            font-weight: 800;
            font-size: 1.18rem;
            color: #ffffff !important;
            letter-spacing: -0.01em;
        }

        .navbar-gis .nav-link {
            font-weight: 600;
            font-size: 0.88rem;
            color: #94a3b8 !important;
            padding: 6px 14px !important;
            border-radius: 20px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-gis .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .navbar-gis .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.25) 0%, rgba(30, 64, 175, 0.35) 100%);
            border: 1px solid rgba(59, 130, 246, 0.4);
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.2);
        }

        .shimmer-gold {
            background: linear-gradient(120deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.45) 50%, rgba(245, 158, 11, 0.15) 100%);
            background-size: 200% 100%;
            animation: shimmerGoldAnim 3s linear infinite;
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #fbbf24;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        @keyframes shimmerGoldAnim {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .live-pulse-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: livePulseAnim 2s infinite;
        }
        @keyframes livePulseAnim {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .btn-gis-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            transition: all 0.2s ease;
        }

        .btn-gis-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.45);
            color: white !important;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid var(--gis-border);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .badge-sector {
            padding: 5px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Responsive Mobile Navbar Styles */
        @media (max-width: 576px) {
            .navbar-gis .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .navbar-brand {
                gap: 8px !important;
                margin-right: 0 !important;
            }
            .brand-logo-container {
                width: 38px !important;
                height: 38px !important;
            }
            .brand-banner-container {
                height: 38px !important;
                width: 190px !important;
                max-width: calc(100vw - 110px) !important;
            }
            .navbar-toggler {
                padding: 4px 8px !important;
                font-size: 1.15rem !important;
            }
        }
    </style>

    @yield('head')
    @stack('styles')
</head>
<body>
    <!-- Top Animated Luxury Accent Line -->
    <div class="top-luxury-line"></div>

    <!-- Navbar Global -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-gis sticky-top py-2">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/map') }}">
                <div class="brand-logo-container d-flex align-items-center justify-content-center rounded-3 overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; border: 1.5px solid rgba(255, 255, 255, 0.35); box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35), 0 0 16px rgba(37, 99, 235, 0.3); background: #ffffff;">
                    <img src="/images/logo.png?v={{ file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time() }}" alt="Lambang Daerah Kabupaten Kepulauan Sangihe" style="width: 100%; height: 100%; object-fit: contain; padding: 2px; display: block;">
                </div>
                <div class="brand-banner-container d-flex align-items-center rounded-3 overflow-hidden flex-shrink-0" style="height: 48px; width: 285px; max-width: min(285px, 62vw); border: 1.2px solid rgba(59, 130, 246, 0.4); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4), 0 0 16px rgba(37, 99, 235, 0.25); background: #050a14;" title="SIPOTENSI SANGIHE - Innovation 2026">
                    <img src="/images/banner.gif?v={{ file_exists(public_path('images/banner.gif')) ? filemtime(public_path('images/banner.gif')) : time() }}" alt="SIPOTENSI SANGIHE - KABUPATEN KEPULAUAN PORTAL INVESTASI RESMI" style="width: 100%; height: 100%; object-fit: cover; object-position: center 50%; display: block;">
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGisContent" aria-controls="navbarGisContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarGisContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('map') || request()->is('/') ? 'active fw-semibold text-white' : '' }}" href="{{ url('/map') }}">
                            <i class="bi bi-map me-1 text-primary"></i> {{ __('messages.interactive_map') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('potensi/bandingkan*') ? 'active fw-semibold text-white' : '' }}" href="{{ route('potensi.compare') }}" title="{{ __('messages.compare_nav_title') }}">
                            <i class="bi bi-arrow-left-right me-1 text-warning"></i> {{ __('messages.compare') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('statistik*') ? 'active fw-semibold text-white' : '' }}" href="{{ route('public.statistics') }}" title="{{ __('messages.statistics_nav_title') }}">
                            <i class="bi bi-bar-chart-fill me-1 text-success"></i> {{ __('messages.statistics') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/export/potensi-investasis') }}" title="{{ __('messages.download_data') }}">
                            <i class="bi bi-download me-1 text-info"></i> {{ __('messages.download_data') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn btn-link text-info d-flex align-items-center gap-1 border-0" type="button" data-bs-toggle="modal" data-bs-target="#cuacaBahariModal">
                            <i class="bi bi-water text-cyan"></i>
                            <span>{{ __('messages.marine_weather_btn') }}</span>
                        </button>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <!-- Language Switcher -->
                    <div class="dropdown me-1">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 dropdown-toggle text-white d-flex align-items-center gap-1 border-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.82rem;">
                            @if(app()->getLocale() === 'en')
                                <span>🇬🇧 English</span>
                            @else
                                <span>🇮🇩 Indonesia</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                                    <span>🇮🇩</span> Bahasa Indonesia
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                                    <span>🇬🇧</span> English (EN)
                                </a>
                            </li>
                        </ul>
                    </div>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-sm btn-info rounded-pill px-3 py-1 dropdown-toggle text-dark fw-bold d-flex align-items-center gap-1 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; background: #1e293b; color: white;">
                                <li>
                                    <div class="px-3 py-2 border-bottom border-secondary" style="font-size: 0.78rem;">
                                        <div class="fw-bold text-white">{{ auth()->user()->name }}</div>
                                        <div class="text-white-50">{{ auth()->user()->perusahaan ?: 'Akun Investor' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item text-white py-2 d-flex align-items-center gap-2" href="{{ route('investor.dashboard') }}">
                                        <i class="bi bi-speedometer2 text-info"></i> {{ __('messages.investor_dashboard') }}
                                    </a>
                                </li>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('operator'))
                                    <li>
                                        <a class="dropdown-item text-white py-2 d-flex align-items-center gap-2" href="{{ url('/admin') }}">
                                            <i class="bi bi-shield-lock text-warning"></i> {{ __('messages.admin_panel') }}
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li>
                                    <form method="POST" action="{{ route('investor.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2 d-flex align-items-center gap-2">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('investor.login') }}" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 text-nowrap d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>{{ __('messages.login') }}</span>
                        </a>
                        <a href="{{ route('investor.register') }}" class="btn btn-sm btn-info rounded-pill px-3 py-1 text-nowrap text-dark fw-bold d-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>{{ __('messages.register_account') }}</span>
                        </a>
                        <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 text-nowrap d-flex align-items-center gap-1 text-white-50" title="Login Khusus Petugas DPMPTSP">
                            <i class="bi bi-shield-lock-fill text-warning"></i>
                            <span class="d-none d-lg-inline">{{ __('messages.admin_login') }}</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    @include('partials.cuaca_bahari_modal')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
