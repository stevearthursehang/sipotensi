@extends('layouts.app')

@section('title', 'Peta Interaktif Potensi Investasi - Kab. Kepulauan')

@push('styles')
<style>
    /* Full height layout */
    .map-wrapper {
        position: relative;
        height: calc(100vh - 58px);
        height: calc(100dvh - 58px);
        min-height: 450px;
        width: 100%;
        overflow: hidden;
        background-color: #aad3df;
    }

    #map {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        min-height: 450px;
        z-index: 1;
        background-color: #aad3df !important;
    }

    /* Floating Side Panel */
    .floating-panel {
        position: absolute;
        top: 16px;
        left: 16px;
        width: 380px;
        max-width: calc(100vw - 32px);
        max-height: calc(100vh - 90px);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.8);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .floating-panel.collapsed {
        transform: translateX(-105%);
    }

    .panel-toggle-btn {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 999;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        display: none;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .panel-toggle-btn.visible {
        display: flex;
    }

    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-body {
        padding: 16px 20px;
        overflow-y: auto;
        flex: 1;
    }

    /* Stats bar top right */
    .floating-stats {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 1000;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
        pointer-events: none;
    }

    .stat-badge {
        background: rgba(10, 15, 29, 0.88);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        color: white;
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 600;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: auto;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.4);
    }

    .stat-badge .stat-value {
        color: #38bdf8;
        font-weight: 800;
    }

    /* Floating Executive GIS Tools Dock */
    .gis-tools-dock {
        position: absolute;
        top: 68px;
        right: 16px;
        z-index: 1000;
        background: rgba(10, 15, 29, 0.88);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 16px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
        padding: 5px 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        pointer-events: auto;
    }
    .dock-group {
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .dock-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 0.72rem;
        padding: 6px 10px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .dock-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-1px);
    }
    .dock-btn.active {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.35) 0%, rgba(30, 64, 175, 0.5) 100%);
        color: #60a5fa;
        border: 1px solid rgba(96, 165, 250, 0.45);
        box-shadow: 0 0 14px rgba(37, 99, 235, 0.35);
        font-weight: 700;
    }
    .dock-divider {
        width: 1px;
        height: 28px;
        background: rgba(255, 255, 255, 0.12);
        margin: 0 4px;
    }
    .dock-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* Measurement Notification Banner */
    .measure-banner {
        position: absolute;
        top: 16px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1040;
        background: rgba(10, 15, 29, 0.94);
        backdrop-filter: blur(12px);
        color: white;
        border: 1px solid #06b6d4;
        border-radius: 30px;
        padding: 8px 20px;
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.85rem;
    }

    /* Sector Badges */
    .badge-pariwisata { background: #dcfce7; color: #15803d; }
    .badge-perikanan { background: #e0f2fe; color: #0369a1; }
    .badge-pertanian { background: #fef3c7; color: #b45309; }
    .badge-peternakan { background: #fae8ff; color: #86198f; }
    .badge-industri { background: #f1f5f9; color: #475569; }
    .badge-energi { background: #ffedd5; color: #c2410c; }

    /* Custom Leaflet Marker */
    .custom-marker-pin {
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        background: #2563eb;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin: -24px 0 0 -18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        border: 2px solid #ffffff;
    }

    .custom-marker-pin i {
        transform: rotate(45deg);
        color: white;
        font-size: 16px;
    }

    /* Popup Styling */
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .leaflet-popup-content {
        margin: 0;
        line-height: 1.5;
        width: 290px !important;
    }

    .popup-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 12px 16px;
    }

    .popup-body {
        padding: 14px 16px;
        font-size: 0.85rem;
    }

    /* Card Item in List Tab */
    .potensi-item-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .potensi-item-card:hover {
        border-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }

    /* Scrollbar */
    .panel-body::-webkit-scrollbar {
        width: 6px;
    }
    .panel-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    /* ===================================================
       3D MODE & DIGITAL TWIN STYLING
       =================================================== */
    .btn-3d-feature {
        position: relative;
    }
    .badge-3d-glow {
        position: absolute;
        top: -3px;
        right: -3px;
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: white;
        font-size: 0.52rem;
        padding: 1px 4px;
        border-radius: 6px;
        font-weight: 800;
        letter-spacing: 0.05em;
        box-shadow: 0 0 8px rgba(245, 158, 11, 0.7);
        animation: pulse3d 2s infinite ease-in-out;
    }
    @keyframes pulse3d {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.85; }
    }
    .dock-btn.btn-3d-active {
        background: linear-gradient(135deg, rgba(234, 179, 8, 0.35) 0%, rgba(202, 138, 4, 0.55) 100%) !important;
        color: #fef08a !important;
        border: 1px solid rgba(253, 224, 71, 0.6) !important;
        box-shadow: 0 0 16px rgba(234, 179, 8, 0.45) !important;
        font-weight: 700;
    }

    /* Map 3D Perspective Wrapper */
    .map-wrapper.mode-3d-active {
        perspective: 1100px;
        background: radial-gradient(circle at 50% 15%, #1e3a8a 0%, #030712 100%);
    }
    .map-wrapper.mode-3d-active #map {
        transform: rotateX(var(--map-pitch, 48deg)) rotateZ(var(--map-bearing, -6deg)) scale(1.08);
        transform-origin: center 75%;
        transition: transform 0.45s cubic-bezier(0.2, 0.8, 0.2, 1);
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.85), 0 0 50px rgba(37, 99, 235, 0.25);
        border-radius: 14px;
        filter: saturate(1.18) contrast(1.08);
    }
    .map-wrapper.mode-3d-active .custom-marker-pin {
        transform: rotateX(calc(-1 * var(--map-pitch, 48deg))) scale(1.22) translateY(-12px);
        transform-origin: center bottom;
        transition: transform 0.3s ease;
        box-shadow: 0 16px 28px rgba(0, 0, 0, 0.6), 0 0 20px rgba(56, 189, 248, 0.9);
        filter: drop-shadow(0 0 12px rgba(56, 189, 248, 0.9));
        animation: markerPulse3D 2.2s infinite ease-in-out;
    }
    @keyframes markerPulse3D {
        0%, 100% { transform: rotateX(calc(-1 * var(--map-pitch, 48deg))) scale(1.22) translateY(-12px); }
        50% { transform: rotateX(calc(-1 * var(--map-pitch, 48deg))) scale(1.3) translateY(-18px); }
    }
    .map-wrapper.mode-3d-active .custom-marker-pin::after {
        content: '';
        position: absolute;
        bottom: -22px;
        left: 50%;
        transform: translateX(-50%);
        width: 2.5px;
        height: 22px;
        background: linear-gradient(to top, rgba(255, 255, 255, 0.95), transparent);
        box-shadow: 0 0 10px #60a5fa, 0 0 16px #38bdf8;
    }

    /* 3D HUD Floating Controller */
    .hud-3d-controller {
        position: absolute;
        bottom: 24px;
        right: 24px;
        width: 290px;
        background: rgba(10, 15, 29, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(253, 224, 71, 0.3);
        border-radius: 18px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6), 0 0 24px rgba(234, 179, 8, 0.2);
        z-index: 1050;
        padding: 12px 14px;
        pointer-events: auto;
        animation: slideUpHud 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideUpHud {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .hud-3d-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .hud-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 0;
    }
    .hud-val {
        font-size: 0.75rem;
        font-weight: 800;
        color: #fef08a;
        font-family: monospace;
    }
    .hud-slider {
        accent-color: #eab308;
        height: 4px;
    }
    .btn-gradient-3d {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #d97706 100%);
        border: 1px solid rgba(253, 224, 71, 0.5);
        transition: all 0.2s ease;
    }
    .btn-gradient-3d:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(234, 179, 8, 0.4);
        color: #ffffff;
    }

    /* Mobile Responsive Optimizations (< 768px) */
    @media (max-width: 767.98px) {
        .map-wrapper {
            height: calc(100vh - 56px);
            height: calc(100dvh - 56px);
            min-height: calc(100vh - 56px);
            background-color: #aad3df;
        }
        #map {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            min-height: 100%;
            background-color: #aad3df !important;
        }
        .floating-panel {
            top: 10px;
            left: 10px;
            width: calc(100vw - 20px);
            max-width: calc(100vw - 20px);
            max-height: calc(100vh - 78px);
            border-radius: 14px;
        }
        .panel-toggle-btn {
            top: 10px;
            left: 10px;
            padding: 8px 14px;
            font-size: 0.8rem;
            border-radius: 20px;
        }
        /* Mobile GIS Dock placed at bottom */
        .gis-tools-dock {
            display: flex !important;
            top: auto !important;
            bottom: 16px !important;
            right: auto !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            max-width: calc(100vw - 20px) !important;
            overflow-x: auto !important;
            padding: 4px 6px !important;
            border-radius: 30px !important;
            background: rgba(10, 15, 29, 0.94) !important;
            backdrop-filter: blur(16px) !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            scrollbar-width: none;
        }
        .gis-tools-dock::-webkit-scrollbar {
            display: none;
        }
        .dock-btn {
            padding: 4px 7px !important;
        }
        .dock-label {
            font-size: 0.58rem !important;
        }
        .dock-divider {
            height: 22px !important;
        }
        .leaflet-control-zoom {
            margin-bottom: 72px !important;
            margin-right: 12px !important;
        }
        .hud-3d-controller {
            bottom: 72px !important;
            right: 10px !important;
            left: 10px !important;
            width: auto !important;
            border-radius: 14px !important;
            padding: 10px 12px !important;
        }
        .leaflet-popup-content {
            width: 270px !important;
            max-width: calc(100vw - 50px) !important;
        }
    }
</style>
@endpush

@section('content')
<div class="map-wrapper">
    <!-- Map Container -->
    <div id="map"></div>

    <!-- Measurement Active Banner -->
    <div id="measureBanner" class="measure-banner d-none">
        <div class="d-flex align-items-center gap-2">
            <span class="live-pulse-dot" style="background: #06b6d4;"></span>
            <span class="fw-bold"><i class="bi bi-rulers text-warning me-1"></i> {{ __('messages.measure_active_title') }}</span>
            <span class="text-white-50">{{ __('messages.measure_active_hint') }}</span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-light rounded-pill py-0 px-2.5" onclick="resetMeasure()">{{ __('messages.measure_recalculate') }}</button>
            <button type="button" class="btn btn-sm btn-info text-dark fw-bold rounded-pill py-0 px-2.5" onclick="toggleMeasureTool(document.getElementById('btnRulerTool'))">{{ __('messages.measure_finish') }}</button>
        </div>
    </div>

    <!-- 3D Perspective HUD Controller -->
    <div id="hud3DController" class="hud-3d-controller d-none">
        <div class="hud-3d-header">
            <div class="d-flex align-items-center gap-2">
                <span class="live-pulse-dot" style="background: #eab308;"></span>
                <span class="fw-bold text-white small"><i class="bi bi-box-fill text-warning me-1"></i> {{ __('messages.hud_3d_active') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="exit3DMode()" title="{{ __('messages.hud_exit_title') }}"></button>
        </div>
        <div class="hud-3d-body">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <label class="hud-label"><i class="bi bi-arrow-up-right-circle text-info me-1"></i> {{ __('messages.hud_pitch') }}</label>
                <span id="lblPitchVal" class="hud-val">48°</span>
            </div>
            <input type="range" class="form-range hud-slider mb-2" id="sliderPitch" min="0" max="65" value="48" oninput="update3DPitch(this.value)">

            <div class="d-flex align-items-center justify-content-between mb-1">
                <label class="hud-label"><i class="bi bi-compass text-warning me-1"></i> {{ __('messages.hud_bearing') }}</label>
                <span id="lblBearingVal" class="hud-val">-6°</span>
            </div>
            <input type="range" class="form-range hud-slider mb-3" id="sliderBearing" min="-45" max="45" value="-6" oninput="update3DBearing(this.value)">

            <div class="d-flex flex-column gap-2">
                <button type="button" id="btnAutoOrbit" class="btn btn-sm btn-outline-warning rounded-pill py-1 d-flex align-items-center justify-content-center gap-1.5" onclick="toggleAutoOrbit()">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>{{ __('messages.hud_orbit_btn') }}</span>
                </button>
                <button type="button" class="btn btn-sm btn-gradient-3d text-white fw-bold rounded-pill py-1.5 d-flex align-items-center justify-content-center gap-1.5 shadow" onclick="openDigitalTwinModal()">
                    <i class="bi bi-badge-3d-fill fs-6"></i>
                    <span>{{ __('messages.hud_open_webgl_btn') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toggle Button (Visible when panel is closed or on mobile) -->
    <button class="panel-toggle-btn" id="panelOpenBtn" onclick="toggleSidebar(true)">
        <i class="bi bi-funnel-fill"></i>
        <span>{{ __('messages.toggle_filter_btn') }}</span>
    </button>

    <!-- Top Right Stats -->
    <div class="floating-stats d-none d-md-flex">
        <button class="stat-badge btn btn-link p-2 text-white text-decoration-none border-0" data-bs-toggle="modal" data-bs-target="#cuacaBahariModal" style="pointer-events: auto; cursor: pointer;" title="Klik untuk buletin maritim BMKG">
            <span class="live-pulse-dot me-1"></span>
            <i class="bi bi-water text-info"></i>
            <span>{{ __('messages.marine_weather_btn') }}: <span class="badge bg-success rounded-pill ms-1" style="font-size:0.68rem;">{{ __('messages.weather_conducive') }}</span></span>
        </button>
        <div class="stat-badge">
            <i class="bi bi-geo-alt-fill text-danger"></i>
            <span>{{ __('messages.kelurahan_count') }}: <span class="stat-value" id="statKelurahan">{{ $statistics['total_kelurahans'] ?? 0 }}</span></span>
        </div>
        <div class="stat-badge">
            <i class="bi bi-lightbulb-fill text-warning"></i>
            <span>{{ __('messages.investment_opportunities') }}: <span class="stat-value" id="statPotensi">{{ $statistics['total_potensi'] ?? 0 }}</span></span>
        </div>
        <div class="stat-badge">
            <i class="bi bi-cash-stack text-success"></i>
            <span>{{ __('messages.total_investment_value') }}: <span class="stat-value" id="statNilai">Rp {{ number_format(($statistics['total_nilai'] ?? 0) / 1000000000, 1, app()->getLocale() === 'en' ? '.' : ',', app()->getLocale() === 'en' ? ',' : '.') }} {{ app()->getLocale() === 'en' ? 'B' : 'M' }}</span></span>
        </div>
    </div>

    <!-- Floating Executive GIS Tools Dock -->
    <div class="gis-tools-dock d-none d-md-flex" id="gisToolDock">
        <!-- Basemap Switcher Group -->
        <div class="dock-group" title="{{ __('messages.dock_view_mode_title') }}">
            <button type="button" class="dock-btn active" data-basemap="osm" onclick="switchBasemap('osm', this)">
                <i class="bi bi-map"></i>
                <span class="dock-label">{{ __('messages.dock_streets') }}</span>
            </button>
            <button type="button" class="dock-btn" data-basemap="satellite" onclick="switchBasemap('satellite', this)">
                <i class="bi bi-globe-americas text-warning"></i>
                <span class="dock-label">{{ __('messages.dock_satellite') }}</span>
            </button>
            <button type="button" class="dock-btn" data-basemap="dark" onclick="switchBasemap('dark', this)">
                <i class="bi bi-moon-stars-fill text-info"></i>
                <span class="dock-label">{{ __('messages.dock_dark') }}</span>
            </button>
            <button type="button" class="dock-btn btn-3d-feature" id="btn3DMode" onclick="toggle3DExperience(this)" title="{{ __('messages.dock_3d_title') }}">
                <i class="bi bi-box-fill text-warning"></i>
                <span class="dock-label">{{ __('messages.dock_3d_mode') }}</span>
                <span class="badge-3d-glow">3D</span>
            </button>
        </div>

        <div class="dock-divider"></div>

        <!-- Action Tools Group -->
        <div class="dock-group">
            <button type="button" class="dock-btn" id="btnRulerTool" onclick="toggleMeasureTool(this)" title="{{ __('messages.dock_measure_title') }}">
                <i class="bi bi-rulers text-warning"></i>
                <span class="dock-label">{{ __('messages.dock_measure') }}</span>
            </button>
            <button type="button" class="dock-btn" onclick="toggleFullScreen()" title="{{ __('messages.dock_fullscreen_title') }}">
                <i class="bi bi-fullscreen text-info" id="fsIcon"></i>
                <span class="dock-label">{{ __('messages.dock_fullscreen') }}</span>
            </button>
            <button type="button" class="dock-btn" onclick="resetSangiheView()" title="{{ __('messages.dock_center_map_title') }}">
                <i class="bi bi-crosshair text-danger"></i>
                <span class="dock-label">{{ __('messages.dock_center_map') }}</span>
            </button>
        </div>
    </div>

    <!-- Floating Filter & List Panel -->
    <div class="floating-panel" id="sidebarPanel">
        <!-- Panel Header -->
        <div class="panel-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-sliders2 text-primary fs-5"></i>
                <h6 class="mb-0 fw-bold text-dark">{{ __('messages.explore_title') }}</h6>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button class="btn btn-sm btn-light border-0 text-muted rounded-circle" onclick="toggleSidebar(false)" title="{{ __('messages.hide_panel') }}">
                    <i class="bi bi-chevron-left fs-6"></i>
                </button>
            </div>
        </div>

        <!-- Panel Nav Tabs -->
        <ul class="nav nav-pills nav-fill p-2 bg-light border-bottom" id="panelTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-1 px-3 fw-semibold fs-7" id="list-tab" data-bs-toggle="tab" data-bs-target="#tab-list" type="button" role="tab">
                    <i class="bi bi-list-ul me-1"></i> {{ __('messages.tab_list') }} (<span id="potensiCount">{{ count($initialPotensi ?? []) }}</span>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-1 px-3 fw-semibold fs-7" id="filter-tab" data-bs-toggle="tab" data-bs-target="#tab-filter" type="button" role="tab">
                    <i class="bi bi-funnel me-1"></i> {{ __('messages.tab_filter') }}
                </button>
            </li>
        </ul>

        <!-- Panel Body -->
        <div class="panel-body">
            <div class="tab-content" id="panelTabsContent">
                <!-- TAB 1: FILTERS -->
                <div class="tab-pane fade" id="tab-filter" role="tabpanel">
                    <!-- Search Input -->
                    <div class="mb-3 position-relative">
                        <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.search_label') }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="{{ __('messages.search_placeholder') }}">
                            <button class="btn btn-outline-secondary border" type="button" id="clearSearchBtn" title="{{ __('messages.clear_search') }}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sector Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.sector_label') }}</label>
                        <select id="jenisPotensiFilter" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_sectors_count', ['count' => count($sektors)]) }}</option>
                            @php
                                $sectorTranslations = [
                                    'Pertanian' => __('messages.sector_agriculture'),
                                    'Pertanian & Perkebunan' => __('messages.sector_agriculture'),
                                    'Perikanan' => __('messages.sector_fishery'),
                                    'Perikanan & Kelautan' => __('messages.sector_fishery'),
                                    'Pariwisata' => __('messages.sector_tourism'),
                                    'Pariwisata & Budaya' => __('messages.sector_tourism'),
                                    'Peternakan' => __('messages.sector_livestock'),
                                    'Industri' => __('messages.sector_industry'),
                                    'Industri & Pengolahan' => __('messages.sector_industry'),
                                    'Energi' => __('messages.sector_energy'),
                                    'Energi Terbarukan' => __('messages.sector_energy'),
                                    'Perdagangan & Jasa' => __('messages.sector_trade'),
                                    'Perdagangan & Logistik' => __('messages.sector_trade'),
                                ];
                            @endphp
                            @foreach($sektors as $sektor)
                                @php
                                    $translatedSektor = $sectorTranslations[$sektor->nama] ?? $sektor->nama;
                                @endphp
                                <option value="{{ $sektor->nama }}">{{ $translatedSektor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kecamatan Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.district_label') }}</label>
                        <select id="kecamatanFilter" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_districts') }}</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Skala Peluang & Status Lahan -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.scale_label') }}</label>
                            <select id="skalaFilter" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_scales') }}</option>
                                <option value="kecil">{{ __('messages.scale_small') }}</option>
                                <option value="menengah">{{ __('messages.scale_medium') }}</option>
                                <option value="besar">{{ __('messages.scale_large') }}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.land_status_label') }}</label>
                            <select id="statusLahanFilter" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_land_status') }}</option>
                                <option value="tersedia">{{ __('messages.land_available') }}</option>
                                <option value="dalam_proses">{{ __('messages.land_in_process') }}</option>
                                <option value="tidak_tersedia">{{ __('messages.land_unavailable') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ketersediaan Infrastruktur -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-dark mb-2 d-flex align-items-center gap-1">
                            <i class="bi bi-buildings text-primary"></i> {{ __('messages.facilities_label') }}
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-check form-switch small">
                                    <input class="form-check-input" type="checkbox" id="aksesJalanFilter" value="1">
                                    <label class="form-check-label" for="aksesJalanFilter">{{ __('messages.facility_road') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch small">
                                    <input class="form-check-input" type="checkbox" id="listrikFilter" value="1">
                                    <label class="form-check-label" for="listrikFilter">{{ __('messages.facility_electricity') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch small">
                                    <input class="form-check-input" type="checkbox" id="airBersihFilter" value="1">
                                    <label class="form-check-label" for="airBersihFilter">{{ __('messages.facility_water') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch small">
                                    <input class="form-check-input" type="checkbox" id="internetFilter" value="1">
                                    <label class="form-check-label" for="internetFilter">{{ __('messages.facility_internet') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Urutkan Berdasarkan -->
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.sort_label') }}</label>
                            <select id="sortBy" class="form-select form-select-sm">
                                <option value="nama">{{ __('messages.sort_name') }}</option>
                                <option value="nilai_estimasi">{{ __('messages.sort_value') }}</option>
                                <option value="roi_estimasi">{{ __('messages.sort_roi') }}</option>
                                <option value="created_at">{{ __('messages.sort_latest') }}</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-bold text-secondary mb-1">{{ __('messages.order_label') }}</label>
                            <select id="sortDirection" class="form-select form-select-sm">
                                <option value="asc">{{ __('messages.order_asc') }}</option>
                                <option value="desc">{{ __('messages.order_desc') }}</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-sm btn-outline-secondary w-100 mt-2" onclick="resetFilters()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('messages.reset_filter') }}
                    </button>
                </div>

                <!-- TAB 2: LIST VIEW (Default Active) -->
                <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
                    <div id="potensiCardsList">
                        @if(isset($initialPotensi) && count($initialPotensi) > 0)
                            @foreach($initialPotensi as $item)
                                @php
                                    $sector = is_array($item) ? ($item['jenis_potensi'] ?? '') : ($item->jenis_potensi ?? '');
                                    $theme = ['bg' => '#2563eb', 'light' => '#dbeafe', 'icon' => 'bi-geo-alt'];
                                    if (stripos($sector, 'Pertanian') !== false) {
                                        $theme = ['bg' => '#b45309', 'light' => '#fef3c7', 'icon' => 'bi-flower1'];
                                    } elseif (stripos($sector, 'Perikanan') !== false) {
                                        $theme = ['bg' => '#0369a1', 'light' => '#e0f2fe', 'icon' => 'bi-water'];
                                    } elseif (stripos($sector, 'Pariwisata') !== false) {
                                        $theme = ['bg' => '#15803d', 'light' => '#dcfce7', 'icon' => 'bi-sun'];
                                    } elseif (stripos($sector, 'Peternakan') !== false) {
                                        $theme = ['bg' => '#86198f', 'light' => '#fae8ff', 'icon' => 'bi-egg-fried'];
                                    } elseif (stripos($sector, 'Industri') !== false) {
                                        $theme = ['bg' => '#475569', 'light' => '#f1f5f9', 'icon' => 'bi-gear-wide-connected'];
                                    } elseif (stripos($sector, 'Energi') !== false) {
                                        $theme = ['bg' => '#c2410c', 'light' => '#ffedd5', 'icon' => 'bi-lightning-charge'];
                                    }

                                    $val = (float)(is_array($item) ? ($item['nilai_estimasi'] ?? $item['nilai_estimasi_investasi'] ?? 0) : ($item->nilai_estimasi ?? $item->nilai_estimasi_investasi ?? 0));
                                    $isEn = app()->getLocale() === 'en';
                                    $billionWord = $isEn ? __('messages.currency_billion') : 'Miliar';
                                    $millionWord = $isEn ? __('messages.currency_million') : 'Juta';
                                    $decPoint = $isEn ? '.' : ',';
                                    $thousandsSep = $isEn ? ',' : '.';
                                    if ($val >= 1000000000) {
                                        $formattedRupiah = 'Rp ' . number_format($val / 1000000000, 1, $decPoint, $thousandsSep) . ' ' . $billionWord;
                                    } elseif ($val >= 1000000) {
                                        $formattedRupiah = 'Rp ' . number_format($val / 1000000, 1, $decPoint, $thousandsSep) . ' ' . $millionWord;
                                    } else {
                                        $formattedRupiah = 'Rp ' . number_format($val, 0, $decPoint, $thousandsSep);
                                    }
                                    $itemId = is_array($item) ? $item['id'] : $item->id;
                                    $itemNama = is_array($item) ? $item['nama'] : $item->nama;
                                    $rawSkala = is_array($item) ? ($item['skala_peluang'] ?? '') : ($item->skala_peluang ?? '');
                                    $skalaMap = [
                                        'kecil' => __('messages.scale_small'),
                                        'menengah' => __('messages.scale_medium'),
                                        'besar' => __('messages.scale_large'),
                                    ];
                                    $itemSkala = $skalaMap[strtolower($rawSkala)] ?? $rawSkala;
                                    $itemKel = is_array($item) ? ($item['kelurahan']['nama'] ?? '-') : ($item->kelurahan->nama ?? '-');
                                    $itemKec = is_array($item) ? ($item['kelurahan']['kecamatan'] ?? '-') : ($item->kelurahan->kecamatan ?? '-');
                                    $lat = (float)(is_array($item) ? ($item['latitude'] ?? 0) : ($item->latitude ?? 0));
                                    $lng = (float)(is_array($item) ? ($item['longitude'] ?? 0) : ($item->longitude ?? 0));
                                    $displaySector = $sectorTranslations[$sector] ?? ($sector ?: 'Potensi');
                                @endphp
                                <div class="potensi-item-card">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge-sector" style="background: {{ $theme['light'] }}; color: {{ $theme['bg'] }};">
                                            <i class="bi {{ $theme['icon'] }}"></i> {{ $displaySector }}
                                        </span>
                                        <span class="badge bg-light text-muted border text-capitalize" style="font-size:0.68rem;">{{ $itemSkala }}</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">{{ $itemNama }}</h6>
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-geo-alt"></i> {{ $itemKel . ', ' . ($isEn ? 'Dist. ' : 'Kec. ') . $itemKec }}
                                    </small>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="fw-bold text-success fs-7">{{ $formattedRupiah }}</span>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-warning py-0 px-2 rounded-pill" onclick="toggleCompareItem({{ $itemId }})" title="{{ __('messages.btn_compare') }}" style="font-size:0.75rem;">
                                                <i class="bi bi-arrow-left-right"></i>
                                            </button>
                                            @if($lat != 0 && $lng != 0)
                                                <button class="btn btn-sm btn-light border py-0 px-2" onclick="focusMarker({{ $lat }}, {{ $lng }})" title="{{ __('messages.btn_focus') }}">
                                                    <i class="bi bi-crosshair"></i>
                                                </button>
                                                <button class="btn btn-sm btn-light border py-0 px-2 text-warning" onclick="openDigitalTwinById({{ $itemId }})" title="{{ __('messages.btn_3d') }}">
                                                    <i class="bi bi-box-fill"></i>
                                                </button>
                                            @endif
                                            <a href="/potensi/{{ $itemId }}" class="btn btn-sm btn-primary py-0 px-2" style="font-size:0.75rem;">
                                                {{ __('messages.btn_detail') }} <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <p class="small mt-2 mb-0">{{ __('messages.loading_potensi') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Bar untuk Bandingkan Peluang -->
<div id="compareFloatingBar" class="d-none position-fixed bottom-0 start-50 translate-middle-x mb-4 shadow-lg rounded-pill px-4 py-2 text-white align-items-center gap-3 animate__animated animate__fadeInUp" style="z-index: 1060; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.15);">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left-right text-warning fs-5"></i>
        <span class="small"><strong id="compareCount" class="text-warning">0</strong> {{ __('messages.compare_bar_title') }}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-warning rounded-pill px-3 py-1 fw-bold text-dark shadow-sm" onclick="goToCompare()">
            {{ __('messages.compare_now') }} <i class="bi bi-arrow-right ms-1"></i>
        </button>
        <button class="btn btn-sm btn-outline-light rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="clearCompare()" title="{{ __('messages.compare_cancel') }}">
            <i class="bi bi-x fs-6"></i>
        </button>
    </div>
</div>

@auth
<!-- Modal Form Minat Investor Standar BKPM / DPMPTSP (Untuk Investor Terautentikasi) -->
<div class="modal fade" id="minatModal" tabindex="-1" aria-labelledby="minatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg text-white" style="background: #1e293b;">
            <div class="modal-header border-0 rounded-top-4 py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-briefcase-fill text-warning fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" id="minatModalLabel">{{ __('messages.interest_modal_title') }}</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;">{{ __('messages.interest_modal_subtitle') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                <form id="minatForm" onsubmit="event.preventDefault(); submitMinatForm();">
                    <input type="hidden" name="potensi_investasi_id" id="modalPotensiId">
                    <input type="hidden" name="kelurahan_id" id="modalKelurahanId">

                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-3 d-flex align-items-center justify-content-between" id="modalPotensiInfo" style="background: rgba(14, 165, 233, 0.15); border-color: rgba(14, 165, 233, 0.3); color: #7dd3fc;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span id="modalPotensiName" class="fw-semibold">{{ __('messages.investment_opportunities') }}</span>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2.5 py-1">
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                    <!-- 1. Identitas Pemohon -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_pic_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="nama" value="{{ auth()->user()->name }}" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_position') }}</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="jabatan" value="{{ auth()->user()->jabatan }}" placeholder="Direktur / Manajer Investasi">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_company') }}</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="perusahaan" value="{{ auth()->user()->perusahaan }}" placeholder="PT / CV / BUMN / Perorangan">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_capital_class') }}</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="jenis_investor">
                                <option value="pmdn" selected>PMDN (Domestik)</option>
                                <option value="pma">PMA (Asing)</option>
                                <option value="perorangan">Perorangan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_origin') }}</label>
                            <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" name="asal_negara" placeholder="Jakarta / Singapura">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm bg-dark border-secondary text-white" name="email" value="{{ auth()->user()->email }}" required placeholder="nama@email.com">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_phone') }} <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-sm bg-dark border-secondary text-white" name="telepon" value="{{ auth()->user()->telepon }}" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- 2. Rencana Modal & Kerjasama -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_planned_capital') }}</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="rentang_modal">
                                <option value="< Rp 1 Miliar">< Rp 1 {{ app()->getLocale() === 'en' ? 'Billion' : 'Miliar' }}</option>
                                <option value="Rp 1 - 5 Miliar" selected>Rp 1 - 5 {{ app()->getLocale() === 'en' ? 'Billion' : 'Miliar' }}</option>
                                <option value="Rp 5 - 25 Miliar">Rp 5 - 25 {{ app()->getLocale() === 'en' ? 'Billion' : 'Miliar' }}</option>
                                <option value="> Rp 25 Miliar">> Rp 25 {{ app()->getLocale() === 'en' ? 'Billion' : 'Miliar' }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_cooperation_model') }}</label>
                            <select class="form-select form-select-sm bg-dark border-secondary text-white" name="model_kerjasama">
                                <option value="sewa_lahan" selected>Sewa Lahan / Aset Pemda (BOT/BTO)</option>
                                <option value="kpbu">KPBU / PPP (Pemerintah-Swasta)</option>
                                <option value="joint_venture">Joint Venture / Kemitraan</option>
                                <option value="mandiri_100">Investasi Mandiri 100%</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-white-50">{{ __('messages.interest_notes') }}</label>
                        <textarea class="form-control form-control-sm bg-dark border-secondary text-white" name="pesan" rows="2" placeholder="Detail..."></textarea>
                    </div>

                    <div id="minatAlert" class="d-none"></div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
                        <a href="https://wa.me/6281234567890?text=Halo%20DPMPTSP%20Sangihe,%20saya%20tertarik%20dengan%20peluang%20investasi" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp DPMPTSP
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">{{ __('messages.interest_btn_cancel') }}</button>
                            <button type="submit" class="btn btn-sm btn-info rounded-pill px-4 fw-bold text-dark" id="submitMinatBtn">
                                <span id="btnText"><i class="bi bi-send-fill me-1"></i> {{ __('messages.interest_btn_submit') }}</span>
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
                        <h6 class="modal-title fw-bold mb-0 text-white" id="minatModalLabel">{{ __('messages.interest_guest_portal_title') }}</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;">{{ __('messages.interest_modal_subtitle') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-10 text-info mb-3">
                    <i class="bi bi-person-badge fs-1"></i>
                </div>
                <h5 class="fw-bold text-white mb-2">{{ __('messages.interest_guest_title') }}</h5>
                <p class="text-white-50 small mb-4">
                    {{ __('messages.interest_guest_desc') }}
                </p>

                <div class="d-grid gap-2 mb-4">
                    <a id="modalGuestLoginBtn" href="{{ route('investor.login') }}" class="btn btn-info py-2 rounded-pill fw-bold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                        <span>{{ __('messages.interest_guest_login') }}</span>
                    </a>
                    <a id="modalGuestRegBtn" href="{{ route('investor.register') }}" class="btn btn-outline-light py-2 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-person-plus-fill text-info fs-5"></i>
                        <span>{{ __('messages.interest_guest_register') }}</span>
                    </a>
                </div>

                <div class="pt-3 border-top border-secondary">
                    <small class="text-white-50 d-block mb-2">{{ __('messages.interest_guest_wa_help') }}</small>
                    <a id="modalGuestWaBtn" href="https://wa.me/6281234567890" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-whatsapp"></i> {{ __('messages.interest_guest_wa_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
        </div>
    </div>
</div>

<!-- 3D Digital Twin WebGL Modal -->
<div class="modal fade" id="modalDigitalTwin" tabindex="-1" aria-labelledby="modalDigitalTwinLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-white border-0">
            <div class="modal-header border-secondary py-2 px-3 d-flex justify-content-between align-items-center" style="background: rgba(10, 15, 29, 0.95); z-index: 1060;">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning fs-5">
                            <i class="bi bi-box-fill"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-white mb-0" id="modalDigitalTwinLabel">
                                {{ __('messages.modal_3d_title') }}
                            </h6>
                            <small class="text-white-50" style="font-size: 0.72rem;">
                                {{ __('messages.modal_3d_subtitle') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- 3D View Control Bar -->
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-light active" id="btnStyleRealistic" onclick="set3DStyle('realistic')">
                            <i class="bi bi-sun-fill text-warning me-1"></i> {{ __('messages.style_day') }}
                        </button>
                        <button type="button" class="btn btn-outline-light" id="btnStyleNight" onclick="set3DStyle('night')">
                            <i class="bi bi-moon-stars-fill text-info me-1"></i> {{ __('messages.style_night') }}
                        </button>
                        <button type="button" class="btn btn-outline-light" id="btnStyleCyber" onclick="set3DStyle('cyber')">
                            <i class="bi bi-grid-3x3 text-success me-1"></i> {{ __('messages.style_hologram') }}
                        </button>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-info dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-camera-video me-1"></i> {{ __('messages.camera_focus') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
                            <li><a class="dropdown-item py-1.5" href="javascript:void(0)" onclick="focus3DCamera('all')"><i class="bi bi-globe me-2 text-primary"></i> {{ __('messages.camera_all') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-1.5" href="javascript:void(0)" onclick="focus3DCamera('tahuna')"><i class="bi bi-water me-2 text-info"></i> {{ __('messages.camera_tahuna') }}</a></li>
                            <li><a class="dropdown-item py-1.5" href="javascript:void(0)" onclick="focus3DCamera('dagho')"><i class="bi bi-building me-2 text-success"></i> {{ __('messages.camera_dagho') }}</a></li>
                            <li><a class="dropdown-item py-1.5" href="javascript:void(0)" onclick="focus3DCamera('bukide')"><i class="bi bi-tree me-2 text-warning"></i> {{ __('messages.camera_bukide') }}</a></li>
                            <li><a class="dropdown-item py-1.5" href="javascript:void(0)" onclick="focus3DCamera('mahangetang')"><i class="bi bi-fire me-2 text-danger"></i> {{ __('messages.camera_mahangetang') }}</a></li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill" id="btnModalAutoRotate" onclick="toggleModal3DRotation()" title="Rotasi Otomatis 360°">
                        <i class="bi bi-arrow-repeat"></i> {{ __('messages.orbit_auto') }}
                    </button>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-0 position-relative overflow-hidden" style="background: #030712; height: calc(100vh - 58px);">
                <!-- 3D WebGL Canvas Container -->
                <div id="threejsContainer" style="width: 100%; height: 100%; cursor: grab;"></div>

                <!-- 3D Navigation Overlay Help -->
                <div class="position-absolute bottom-0 start-0 m-3 p-2.5 rounded-3 bg-dark bg-opacity-75 border border-secondary text-white-50 small" style="backdrop-filter: blur(8px); font-size: 0.72rem; pointer-events: none; z-index: 10;">
                    <div><strong class="text-white"><i class="bi bi-mouse me-1"></i> {{ __('messages.nav_guide_title') }}</strong></div>
                    <div>&bull; {{ __('messages.nav_guide_rotate') }}</div>
                    <div>&bull; {{ __('messages.nav_guide_zoom') }}</div>
                    <div>&bull; {{ __('messages.nav_guide_pan') }}</div>
                    <div>&bull; {{ __('messages.nav_guide_click') }}</div>
                </div>

                <!-- Project Info Popover in 3D Scene -->
                <div id="card3DProjectInfo" class="position-absolute top-0 end-0 m-3 p-3 rounded-3 bg-dark bg-opacity-95 border border-info shadow-lg text-white d-none" style="width: 320px; backdrop-filter: blur(16px); z-index: 20;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span id="badge3DSector" class="badge bg-primary">Sektor</span>
                        <button type="button" class="btn-close btn-close-white btn-sm" onclick="close3DProjectCard()"></button>
                    </div>
                    <h6 id="title3DProject" class="fw-bold mb-1 text-info">{{ __('messages.proj_name') }}</h6>
                    <small id="loc3DProject" class="text-white-50 d-block mb-2"><i class="bi bi-geo-alt"></i> {{ __('messages.proj_location') }}</small>
                    <div class="p-2 rounded bg-secondary bg-opacity-25 border border-secondary mb-2" style="font-size: 0.8rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white-50">{{ __('messages.proj_capex') }}</span>
                            <strong id="capex3DProject" class="text-success">Rp -</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-white-50">{{ __('messages.proj_roi') }}</span>
                            <strong id="roi3DProject" class="text-warning">- %/Thn</strong>
                        </div>
                    </div>
                    <div class="d-grid gap-1">
                        <a id="link3DProspectus" href="#" target="_blank" class="btn btn-sm btn-primary rounded-pill py-1">
                            <i class="bi bi-file-earmark-pdf me-1"></i> {{ __('messages.download_official_prospectus') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Three.js & 3D libraries (Loaded directly from reliable CDN to eliminate devtunnel streaming issues) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js" defer></script>
<script src="/js/digital-twin-3d.js?v={{ file_exists(public_path('js/digital-twin-3d.js')) ? filemtime(public_path('js/digital-twin-3d.js')) : time() }}" defer></script>
<script>
    const i18n = {
        locale: '{{ app()->getLocale() }}',
        isEn: {{ app()->getLocale() === 'en' ? 'true' : 'false' }},
        distPrefix: '{{ app()->getLocale() === 'en' ? 'Dist. ' : 'Kec. ' }}',
        currencyBillion: '{{ __('messages.currency_billion') }}',
        currencyMillion: '{{ __('messages.currency_million') }}',
        noPotensiTitle: '{{ __('messages.no_potensi_title') }}',
        noPotensiDesc: '{{ __('messages.no_potensi_desc') }}',
        loadError: '{{ __('messages.load_error') }}',
        retry: '{{ __('messages.retry') }}',
        adminArea: '{{ __('messages.admin_area') }}',
        areaSize: '{{ __('messages.area_size') }}',
        population: '{{ __('messages.population') }}',
        inhabitants: '{{ __('messages.inhabitants') }}',
        availInfra: '{{ __('messages.available_infrastructure') }}',
        road: '{{ __('messages.road') }}',
        power: '{{ __('messages.power') }}',
        water: '{{ __('messages.water') }}',
        topPotentials: '{{ __('messages.top_potentials') }}',
        estimatedInvestment: '{{ __('messages.estimated_investment') }}',
        estimatedRoiLabel: '{{ __('messages.estimated_roi_label') }}',
        perYear: '{{ __('messages.per_year') }}',
        landPermitReadiness: '{{ __('messages.land_permit_readiness') }}',
        viewProspectus: '{{ __('messages.view_prospectus') }}',
        visualize3d: '{{ __('messages.visualize_3d') }}',
        compareOpportunity: '{{ __('messages.compare_opportunity') }}',
        expressInterest: '{{ __('messages.express_interest') }}',
        btnDetail: '{{ __('messages.btn_detail') }}',
        btnCompare: '{{ __('messages.btn_compare') }}',
        btnFocus: '{{ __('messages.btn_focus') }}',
        btn3d: '{{ __('messages.btn_3d') }}',
        compareMaxAlert: '{{ __('messages.compare_max_alert') }}',
        measuredDistance: '{{ __('messages.measured_distance') }}',
        meters: '{{ __('messages.meters') }}',
        waveHeight: '{{ __('messages.wave_height') }}',
        windSpeed: '{{ __('messages.wind_speed') }}',
        seaTemp: '{{ __('messages.sea_temp') }}',
        viewBmkgBulletin: '{{ __('messages.view_bmkg_bulletin') }}',
        scaleSmall: '{{ __('messages.scale_small') }}',
        scaleMedium: '{{ __('messages.scale_medium') }}',
        scaleLarge: '{{ __('messages.scale_large') }}',
        landAvailable: '{{ __('messages.land_available') }}',
        landInProcess: '{{ __('messages.land_in_process') }}',
        landUnavailable: '{{ __('messages.land_unavailable') }}',
        sectors: {
            'pertanian': '{{ __('messages.sector_agriculture') }}',
            'perikanan': '{{ __('messages.sector_fishery') }}',
            'pariwisata': '{{ __('messages.sector_tourism') }}',
            'peternakan': '{{ __('messages.sector_livestock') }}',
            'industri': '{{ __('messages.sector_industry') }}',
            'energi': '{{ __('messages.sector_energy') }}',
            'perdagangan': '{{ __('messages.sector_trade') }}'
        }
    };
    window.i18n = i18n;

    const initialPotensiData = @json($initialPotensi ?? []);
    const initialCuacaData = @json($initialCuaca ?? null);
    let map;
    let kelurahanLayer;
    let potensiLayer;
    let cuacaBahariLayer;
    let allPotensiData = [];
    let osmLayer;
    let satelliteLayer;
    let darkLayer;
    let currentBaseLayer;
    let measureActive = false;
    let measurePoints = [];
    let measureLayerGroup;
    let minatModal = null;

    function getMinatModalInstance() {
        if (!minatModal) {
            const el = document.getElementById('minatModal');
            if (el && typeof bootstrap !== 'undefined') {
                minatModal = new bootstrap.Modal(el);
            }
        }
        return minatModal;
    }

    // Color definitions by sector
    const sectorColors = {
        'Pertanian': { bg: '#b45309', light: '#fef3c7', icon: 'bi-flower1' },
        'Perikanan': { bg: '#0369a1', light: '#e0f2fe', icon: 'bi-water' },
        'Pariwisata': { bg: '#15803d', light: '#dcfce7', icon: 'bi-sun' },
        'Peternakan': { bg: '#86198f', light: '#fae8ff', icon: 'bi-egg-fried' },
        'Industri': { bg: '#475569', light: '#f1f5f9', icon: 'bi-gear-wide-connected' },
        'Energi': { bg: '#c2410c', light: '#ffedd5', icon: 'bi-lightning-charge' },
        'Default': { bg: '#2563eb', light: '#dbeafe', icon: 'bi-geo-alt' }
    };

    function getSectorTheme(sector) {
        if (!sector) return sectorColors['Default'];
        for (let key in sectorColors) {
            if (sector.toLowerCase().includes(key.toLowerCase())) {
                return sectorColors[key];
            }
        }
        return sectorColors['Default'];
    }

    function translateSector(sector) {
        if (!sector) return 'Potensi';
        const s = sector.toLowerCase();
        for (let k in i18n.sectors) {
            if (s.includes(k)) return i18n.sectors[k];
        }
        return sector;
    }

    function translateScale(scale) {
        if (!scale) return '';
        const sc = scale.toLowerCase();
        if (sc === 'kecil') return i18n.scaleSmall;
        if (sc === 'menengah') return i18n.scaleMedium;
        if (sc === 'besar') return i18n.scaleLarge;
        return scale;
    }

    function translateLandStatus(status) {
        if (!status) return '-';
        const st = status.toLowerCase();
        if (st.includes('tersedia') && !st.includes('tidak')) return i18n.landAvailable;
        if (st.includes('proses')) return i18n.landInProcess;
        if (st.includes('tidak')) return i18n.landUnavailable;
        return status.replace(/_/g, ' ');
    }

    function formatRupiah(num) {
        if (!num) return 'Rp 0';
        const loc = i18n.isEn ? 'en-US' : 'id-ID';
        if (num >= 1000000000) {
            return 'Rp ' + (num / 1000000000).toLocaleString(loc, { maximumFractionDigits: 1 }) + ' ' + i18n.currencyBillion;
        }
        if (num >= 1000000) {
            return 'Rp ' + (num / 1000000).toLocaleString(loc, { maximumFractionDigits: 1 }) + ' ' + i18n.currencyMillion;
        }
        return 'Rp ' + Number(num).toLocaleString(loc);
    }

    function toggleSidebar(open) {
        const panel = document.getElementById('sidebarPanel');
        const openBtn = document.getElementById('panelOpenBtn');
        if (open) {
            panel.classList.remove('collapsed');
            openBtn.classList.remove('visible');
        } else {
            panel.classList.add('collapsed');
            openBtn.classList.add('visible');
        }
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 320);
    }

    let leafletLoadAttempts = 0;
    function initMapPage() {
        if (typeof L === 'undefined') {
            leafletLoadAttempts++;
            const scriptTag = document.getElementById('leafletScript');
            if (scriptTag && !scriptTag._hasLoadListener) {
                scriptTag._hasLoadListener = true;
                scriptTag.addEventListener('load', () => setTimeout(initMapPage, 20));
            }
            if (leafletLoadAttempts === 10) {
                console.warn('Leaflet taking longer to load, injecting CDN fallback...');
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                s.onload = () => setTimeout(initMapPage, 20);
                document.head.appendChild(s);
            }
            if (leafletLoadAttempts < 300) {
                setTimeout(initMapPage, 100);
            }
            return;
        }

        try {
            if (map) return;

            // On mobile devices, start with collapsed sidebar so map is immediately visible
            if (window.innerWidth < 768) {
                toggleSidebar(false);
            }

            // Initialize Map
            map = L.map('map', {
                zoomControl: false,
                preferCanvas: true
            }).setView([3.58, 125.58], 11); // Center on Kepulauan Sangihe

        // Reposition Zoom Control
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Base Maps: Esri World Street Map (Keyless, high-performance global CDN, free from watermarks & 403 blocks)
        osmLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, METI, TomTom'
        }).addTo(map);
        currentBaseLayer = osmLayer;

        satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP',
            maxZoom: 19
        });

        darkLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 16,
            attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ'
        });

        // Initialize Measurement Layer Group
        measureLayerGroup = L.layerGroup().addTo(map);

        // Map Click Listener for Interactive Measurement Tool
        map.on('click', function (e) {
            if (!measureActive) return;

            measurePoints.push(e.latlng);

            // Add glowing circle marker
            L.circleMarker(e.latlng, {
                radius: 6,
                color: '#06b6d4',
                fillColor: '#ffffff',
                fillOpacity: 1,
                weight: 3
            }).addTo(measureLayerGroup);

            if (measurePoints.length >= 2) {
                const p1 = measurePoints[measurePoints.length - 2];
                const p2 = measurePoints[measurePoints.length - 1];
                const distM = p1.distanceTo(p2);
                const distKm = (distM / 1000).toFixed(2);

                L.polyline([p1, p2], {
                    color: '#06b6d4',
                    weight: 4,
                    dashArray: '6, 8',
                    opacity: 0.95
                }).addTo(measureLayerGroup);

                const midLat = (p1.lat + p2.lat) / 2;
                const midLng = (p1.lng + p2.lng) / 2;

                L.popup({ autoClose: false, closeOnClick: false })
                    .setLatLng([midLat, midLng])
                    .setContent(`
                        <div class="p-1 text-center" style="font-size:0.78rem;">
                            <strong class="text-dark"><i class="bi bi-rulers text-info me-1"></i> ${i18n.measuredDistance}</strong>
                            <div class="fs-6 fw-bold text-primary mt-1">${distKm} km</div>
                            <small class="text-muted">(${Math.round(distM).toLocaleString(i18n.isEn ? 'en-US' : 'id-ID')} ${i18n.meters})</small>
                        </div>
                    `)
                    .addTo(measureLayerGroup);
            }
        });

        // Animated CountUp for Top Metrics
        const totalKel = {{ $statistics['total_kelurahans'] ?? 168 }};
        const totalPot = {{ $statistics['total_potensi'] ?? 29 }};
        const totalNilaiM = {{ round(($statistics['total_nilai'] ?? 0) / 1000000000, 1) }};

        animateCountUp('statKelurahan', totalKel, false);
        animateCountUp('statPotensi', totalPot, false);
        animateCountUp('statNilai', totalNilaiM, true);

        // Keyboard Shortcut: press '/' to focus search
        document.addEventListener('keydown', function (e) {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                toggleSidebar(true);
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        // Ensure map renders tiles fully across initial mobile viewport paints
        [100, 300, 600, 1200].forEach(delay => {
            setTimeout(() => { if (map) map.invalidateSize(); }, delay);
        });
        window.addEventListener('resize', () => { if (map) map.invalidateSize(); });
        window.addEventListener('orientationchange', () => { setTimeout(() => { if (map) map.invalidateSize(); }, 250); });

        // Layer Groups
        kelurahanLayer = L.geoJSON(null, {
            style: function () {
                return {
                    color: '#2563eb',
                    weight: 2,
                    opacity: 0.8,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.12
                };
            },
            onEachFeature: function (feature, layer) {
                const p = feature.properties || {};
                const infra = p.infrastruktur || {};

                layer.on('mouseover', function () {
                    layer.setStyle({ weight: 3, fillOpacity: 0.25, color: '#1d4ed8' });
                });
                layer.on('mouseout', function () {
                    layer.setStyle({ weight: 2, fillOpacity: 0.12, color: '#2563eb' });
                });

                const popupHtml = `
                    <div class="popup-header">
                        <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-0" style="font-size:0.7rem;">${i18n.adminArea}</span>
                        <h6 class="fw-bold mb-0 text-white mt-1">${p.nama || 'Kelurahan'}</h6>
                        <small class="text-white-50"><i class="bi bi-geo-alt"></i> ${i18n.distPrefix}${p.kecamatan || '-'}</small>
                    </div>
                    <div class="popup-body">
                        <div class="row g-2 mb-2 pb-2 border-bottom">
                            <div class="col-6">
                                <small class="text-muted d-block">${i18n.areaSize}</small>
                                <span class="fw-bold">${p.luas_wilayah ? Number(p.luas_wilayah).toLocaleString(i18n.isEn ? 'en-US' : 'id-ID') + ' Ha' : '-'}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">${i18n.population}</small>
                                <span class="fw-bold">${p.jumlah_penduduk ? Number(p.jumlah_penduduk).toLocaleString(i18n.isEn ? 'en-US' : 'id-ID') + ' ' + i18n.inhabitants : '-'}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">${i18n.availInfra}</small>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge ${infra.akses_jalan ? 'bg-success' : 'bg-light text-muted border'}"><i class="bi bi-signpost-split"></i> ${i18n.road}</span>
                                <span class="badge ${infra.listrik ? 'bg-success' : 'bg-light text-muted border'}"><i class="bi bi-lightning"></i> ${i18n.power}</span>
                                <span class="badge ${infra.air_bersih ? 'bg-success' : 'bg-light text-muted border'}"><i class="bi bi-droplet"></i> ${i18n.water}</span>
                                <span class="badge ${infra.internet ? 'bg-success' : 'bg-light text-muted border'}"><i class="bi bi-wifi"></i> 4G</span>
                            </div>
                        </div>
                        ${p.potensi_investasi && p.potensi_investasi.length > 0 ? `
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted d-block mb-1">${i18n.topPotentials} (${p.potensi_investasi.length}):</small>
                                <ul class="list-unstyled mb-0 small ps-1">
                                    ${p.potensi_investasi.slice(0, 3).map(item => `<li><i class="bi bi-check-circle-fill text-primary" style="font-size:0.75rem;"></i> ${item.nama}</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;
                layer.bindPopup(popupHtml);
            }
        }).addTo(map);

        potensiLayer = L.layerGroup().addTo(map);
        cuacaBahariLayer = L.layerGroup().addTo(map);

        // Layer Switcher Control (Base Maps & Overlays)
        L.control.layers({
            '<i class="bi bi-compass"></i> {{ app()->getLocale() === "en" ? "Street Map (Esri)" : "Peta Jalan (Esri)" }}': osmLayer,
            '<i class="bi bi-moon-stars"></i> {{ app()->getLocale() === "en" ? "Dark Canvas (Esri)" : "Peta Gelap (Esri)" }}': darkLayer,
            '<i class="bi bi-globe-americas"></i> {{ app()->getLocale() === "en" ? "Satellite HD" : "Citra Satelit" }}': satelliteLayer
        }, {
            '<i class="bi bi-pin-map-fill text-primary"></i> {{ __('messages.investment_opportunities') }}': potensiLayer,
            '<i class="bi bi-bounding-box text-info"></i> {{ app()->getLocale() === "en" ? "Districts / Administrative" : "Poligon Kelurahan" }}': kelurahanLayer,
            '🌊 {{ __('messages.marine_weather_btn') }}': cuacaBahariLayer
        }, { position: 'bottomright' }).addTo(map);

        // Load Data
        loadKelurahansGeoJson();
        if (Array.isArray(initialPotensiData) && initialPotensiData.length > 0) {
            renderPotensiData(initialPotensiData, true);
        } else {
            loadPotensiInvestasis();
        }
        loadCuacaBahariMarkers();

        // Attach Event Listeners
        const debouncedLoad = debounce(loadPotensiInvestasis, 250);
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.addEventListener('input', debouncedLoad);

        const clearSearchBtn = document.getElementById('clearSearchBtn');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                loadPotensiInvestasis();
            });
        }

        ['jenisPotensiFilter', 'kecamatanFilter', 'skalaFilter', 'statusLahanFilter',
         'aksesJalanFilter', 'listrikFilter', 'airBersihFilter', 'internetFilter',
         'sortBy', 'sortDirection'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', loadPotensiInvestasis);
        });

        // Handle Modal Minat Form (Safely check if element exists for auth user)
        const minatForm = document.getElementById('minatForm');
        if (minatForm) {
            minatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                submitMinatForm();
            });
        }

        // Auto trigger 3D modal if url has ?open3d parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('open3d')) {
            setTimeout(() => {
                if (typeof openDigitalTwinModal === 'function') openDigitalTwinModal();
            }, 600);
        }
        } catch (err) {
            console.error('Error during initMapPage:', err);
        }
    }

    let mapInitialized = false;
    function safeInitMap() {
        if (mapInitialized) return;
        mapInitialized = true;
        initMapPage();
    }

    // Wait until stylesheets are fully loaded to prevent forced reflow / FOUC warnings
    if (document.readyState === 'complete') {
        safeInitMap();
    } else {
        window.addEventListener('load', safeInitMap);
        // Fallback timer in case external media delays window.load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(safeInitMap, 600);
        });
    }

    // Load Kelurahan Polygons
    function loadKelurahansGeoJson() {
        fetch('/api/kelurahans')
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data && data.features && Array.isArray(data.features) && data.features.length > 0) {
                    kelurahanLayer.clearLayers();
                    kelurahanLayer.addData(data);
                    // Fit map bounds to polygons if markers are empty and dimensions valid
                    if (potensiLayer.getLayers().length === 0 && map && map._loaded) {
                        try {
                            const sz = map.getSize();
                            if (sz && sz.x > 50 && sz.y > 50) {
                                map.fitBounds(kelurahanLayer.getBounds(), { padding: [40, 40] });
                            }
                        } catch (err) {}
                    }
                }
            })
            .catch(err => console.warn('Kelurahan layer info:', err));
    }

    // Render Potensi Investasi Data to Map & List
    function renderPotensiData(data, shouldFitBounds = true) {
        if (!Array.isArray(data)) return;
        allPotensiData = data;
        if (potensiLayer) potensiLayer.clearLayers();

        const countEl = document.getElementById('potensiCount');
        if (countEl) countEl.innerText = data.length;

        const cardsContainer = document.getElementById('potensiCardsList');
        const bounds = [];
        if (cardsContainer) cardsContainer.innerHTML = '';

        if (data.length === 0) {
            if (cardsContainer) {
                cardsContainer.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                        <p class="small mb-1 fw-bold">${i18n.noPotensiTitle}</p>
                        <small class="text-muted">${i18n.noPotensiDesc}</small>
                    </div>
                `;
            }
            return;
        }

        data.forEach(item => {
            const theme = getSectorTheme(item.jenis_potensi);
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            const displaySector = translateSector(item.jenis_potensi);
            const displayScale = translateScale(item.skala_peluang);
            const statusLahanText = translateLandStatus(item.status_lahan);
            const statusIzinText = item.status_perizinan ? item.status_perizinan.replace(/_/g, ' ') : '-';
            const kelNama = item.kelurahan ? (item.kelurahan.nama || '-') : '-';

            // Add Marker to Map if coordinates valid
            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                bounds.push([lat, lng]);

                const customIcon = L.divIcon({
                    className: 'custom-leaflet-marker',
                    html: `<div class="custom-marker-pin" style="background:${theme.bg}"><i class="bi ${theme.icon}"></i></div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    popupAnchor: [0, -32]
                });

                const marker = L.marker([lat, lng], { icon: customIcon });

                const popupHtml = `
                    <div class="popup-header" style="background: linear-gradient(135deg, ${theme.bg} 0%, #0f172a 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge" style="background:rgba(255,255,255,0.25); color:white; font-size:0.7rem;">${displaySector}</span>
                            <span class="badge bg-warning text-dark" style="font-size:0.68rem; text-transform:capitalize;">${displayScale}</span>
                        </div>
                        <h6 class="fw-bold text-white mb-0">${escapeHtml(item.nama)}</h6>
                        <small class="text-white-50"><i class="bi bi-geo-alt"></i> ${escapeHtml(kelNama)}</small>
                    </div>
                    <div class="popup-body">
                        <div class="mb-2">
                            <small class="text-muted d-block">${i18n.estimatedInvestment}</small>
                            <span class="fw-bold text-success fs-6">${formatRupiah(item.nilai_estimasi || item.nilai_estimasi_investasi)}</span>
                        </div>
                        ${item.roi_estimasi ? `
                            <div class="mb-2">
                                <small class="text-muted d-block">${i18n.estimatedRoiLabel}</small>
                                <span class="badge bg-info-subtle text-info-emphasis fw-bold">${item.roi_estimasi}% ${i18n.perYear}</span>
                            </div>
                        ` : ''}
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">${i18n.landPermitReadiness}</small>
                            <span class="badge bg-light text-secondary border me-1">${statusLahanText}</span>
                            <span class="badge bg-light text-secondary border">${statusIzinText}</span>
                        </div>
                        <div class="d-grid gap-1 mt-2">
                            <a href="/potensi/${item.id}" class="btn btn-sm btn-primary rounded-pill py-1">
                                <i class="bi bi-file-earmark-text me-1"></i> ${i18n.viewProspectus}
                            </a>
                            <button class="btn btn-sm btn-outline-info text-dark rounded-pill py-1 fw-bold" onclick="openDigitalTwinById(${item.id})">
                                <i class="bi bi-box-fill text-warning me-1"></i> ${i18n.visualize3d}
                            </button>
                            <button class="btn btn-sm btn-outline-warning text-dark rounded-pill py-1 fw-semibold" onclick="toggleCompareItem(${item.id})">
                                <i class="bi bi-arrow-left-right me-1 text-warning"></i> ${i18n.compareOpportunity}
                            </button>
                            <button class="btn btn-sm btn-outline-dark rounded-pill py-1" onclick="openMinatModalById(${item.id})">
                                <i class="bi bi-envelope-paper-heart me-1"></i> ${i18n.expressInterest}
                            </button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupHtml);
                marker.addTo(potensiLayer);
            }

            // Append card in sidebar tab
            if (cardsContainer) {
                const card = document.createElement('div');
                card.className = 'potensi-item-card';
                const kelInfo = item.kelurahan ? (item.kelurahan.nama + ', ' + i18n.distPrefix + (item.kelurahan.kecamatan || '-')) : '-';
                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="badge-sector" style="background:${theme.light}; color:${theme.bg};">
                            <i class="bi ${theme.icon}"></i> ${displaySector}
                        </span>
                        <span class="badge bg-light text-muted border text-capitalize" style="font-size:0.68rem;">${displayScale}</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 fs-7">${escapeHtml(item.nama)}</h6>
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-geo-alt"></i> ${escapeHtml(kelInfo)}
                    </small>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-success fs-7">${formatRupiah(item.nilai_estimasi || item.nilai_estimasi_investasi)}</span>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-warning py-0 px-2 rounded-pill" onclick="toggleCompareItem(${item.id})" title="${i18n.btnCompare}" style="font-size:0.75rem;">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                            ${!isNaN(lat) && !isNaN(lng) && lat !== 0 ? `
                                <button class="btn btn-sm btn-light border py-0 px-2" onclick="focusMarker(${lat}, ${lng})" title="${i18n.btnFocus}">
                                    <i class="bi bi-crosshair"></i>
                                </button>
                                <button class="btn btn-sm btn-light border py-0 px-2 text-warning" onclick="openDigitalTwinById(${item.id})" title="${i18n.btn3d}">
                                    <i class="bi bi-box-fill"></i>
                                </button>
                            ` : ''}
                            <a href="/potensi/${item.id}" class="btn btn-sm btn-primary py-0 px-2" style="font-size:0.75rem;">
                                ${i18n.btnDetail} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                `;
                cardsContainer.appendChild(card);
            }
        });

        // Auto-fit map to Sangihe opportunity markers with multi-pass layout resilience
        if (shouldFitBounds && bounds.length > 0 && map) {
            const fitMapToMarkers = () => {
                try {
                    map.invalidateSize();
                    map.fitBounds(bounds, { padding: [35, 35], maxZoom: 13 });
                } catch (e) {
                    console.warn('fitBounds attempt:', e);
                }
            };
            fitMapToMarkers();
            setTimeout(fitMapToMarkers, 250);
            setTimeout(fitMapToMarkers, 800);
        }
    }

    // Load Potensi Investasi Points with Network & Fallback Support
    function loadPotensiInvestasis() {
        const search = document.getElementById('searchInput')?.value?.trim() || '';
        const jenisPotensi = document.getElementById('jenisPotensiFilter')?.value || '';
        const kecamatan = document.getElementById('kecamatanFilter')?.value || '';
        const skala = document.getElementById('skalaFilter')?.value || '';
        const statusLahan = document.getElementById('statusLahanFilter')?.value || '';
        const aksesJalan = document.getElementById('aksesJalanFilter')?.checked ? '1' : '';
        const listrik = document.getElementById('listrikFilter')?.checked ? '1' : '';
        const airBersih = document.getElementById('airBersihFilter')?.checked ? '1' : '';
        const internet = document.getElementById('internetFilter')?.checked ? '1' : '';
        const sortBy = document.getElementById('sortBy')?.value || 'nama';
        const sortDirection = document.getElementById('sortDirection')?.value || 'asc';

        const params = new URLSearchParams({
            search: search,
            jenis_potensi: jenisPotensi,
            kecamatan: kecamatan,
            skala_peluang: skala,
            status_lahan: statusLahan,
            akses_jalan: aksesJalan,
            listrik: listrik,
            air_bersih: airBersih,
            internet: internet,
            sort_by: sortBy,
            sort_direction: sortDirection
        });

        const cardsContainer = document.getElementById('potensiCardsList');

        fetch(`/api/potensi-investasi?${params}`)
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (!Array.isArray(data)) throw new Error('Format data tidak sesuai');
                renderPotensiData(data, true);
            })
            .catch(err => {
                console.warn('Network issue fetching from API, using fallback data filter:', err);
                if (Array.isArray(initialPotensiData) && initialPotensiData.length > 0) {
                    let filtered = [...initialPotensiData];
                    if (search) {
                        const s = search.toLowerCase();
                        filtered = filtered.filter(item =>
                            (item.nama && item.nama.toLowerCase().includes(s)) ||
                            (item.jenis_potensi && item.jenis_potensi.toLowerCase().includes(s)) ||
                            (item.kelurahan && item.kelurahan.nama && item.kelurahan.nama.toLowerCase().includes(s)) ||
                            (item.kelurahan && item.kelurahan.kecamatan && item.kelurahan.kecamatan.toLowerCase().includes(s))
                        );
                    }
                    if (jenisPotensi) {
                        filtered = filtered.filter(item => item.jenis_potensi === jenisPotensi);
                    }
                    if (kecamatan) {
                        filtered = filtered.filter(item => item.kelurahan && item.kelurahan.kecamatan === kecamatan);
                    }
                    if (skala) {
                        filtered = filtered.filter(item => item.skala_peluang === skala);
                    }
                    renderPotensiData(filtered, true);
                } else if (cardsContainer) {
                    cardsContainer.innerHTML = `
                        <div class="alert alert-danger py-2 small d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> ${i18n.loadError}
                            </div>
                            <button class="btn btn-sm btn-outline-danger py-0 px-2 fw-semibold" onclick="loadPotensiInvestasis()">
                                <i class="bi bi-arrow-clockwise"></i> ${i18n.retry}
                            </button>
                        </div>
                    `;
                }
            });
    }

    function openMinatModalById(id) {
        const item = allPotensiData.find(p => p.id == id);
        if (item) {
            openMinatModal(item.id, item.nama, item.kelurahan_id);
        }
    }

    function openDigitalTwinById(id) {
        const item = (allPotensiData || []).find(p => p.id == id);
        if (item) {
            if (typeof openDigitalTwinForProject === 'function') {
                openDigitalTwinForProject(
                    item.nama,
                    item.jenis_potensi || '',
                    formatRupiah(item.nilai_estimasi || item.nilai_estimasi_investasi),
                    item.roi_estimasi || 0,
                    `/potensi/${item.id}/pdf`
                );
            } else if (typeof openDigitalTwinModal === 'function') {
                openDigitalTwinModal();
            }
        } else {
            if (typeof openDigitalTwinModal === 'function') {
                openDigitalTwinModal();
            }
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function focusMarker(lat, lng) {
        map.setView([lat, lng], 15, { animate: true });
        potensiLayer.eachLayer(function (layer) {
            if (layer.getLatLng && layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
                layer.openPopup();
            }
        });
        // On small screen collapse sidebar to see map
        if (window.innerWidth < 768) {
            toggleSidebar(false);
        }
    }

    function openMinatModal(potensiId, potensiNama, kelurahanId) {
        @auth
            const idInput = document.getElementById('modalPotensiId');
            if (idInput) idInput.value = potensiId;
            const kelInput = document.getElementById('modalKelurahanId');
            if (kelInput) kelInput.value = kelurahanId || '';
            const nameEl = document.getElementById('modalPotensiName');
            if (nameEl) nameEl.innerText = potensiNama;
            const alertBox = document.getElementById('minatAlert');
            if (alertBox) alertBox.className = 'd-none';
        @else
            const nameEl = document.getElementById('modalPotensiGuestName');
            if (nameEl) nameEl.innerText = potensiNama;
            const loginBtn = document.getElementById('modalGuestLoginBtn');
            if (loginBtn) loginBtn.href = '/investor/login?redirect=' + encodeURIComponent('/potensi/' + potensiId);
            const regBtn = document.getElementById('modalGuestRegBtn');
            if (regBtn) regBtn.href = '/investor/register?redirect=' + encodeURIComponent('/potensi/' + potensiId);
            const waBtn = document.getElementById('modalGuestWaBtn');
            if (waBtn) waBtn.href = 'https://wa.me/6281234567890?text=' + encodeURIComponent('Halo DPMPTSP Sangihe, saya ingin berkonsultasi mengenai peluang investasi ' + potensiNama);
        @endauth
        const modal = getMinatModalInstance();
        if (modal) modal.show();
    }

    function submitMinatForm() {
        const form = document.getElementById('minatForm');
        if (!form) return;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitMinatBtn');
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
                alertBox.className = 'alert alert-success py-2 px-3 small rounded-3 mb-0';
                alertBox.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div><i class="bi bi-check-circle-fill me-1"></i> ${data.message || 'Pernyataan minat resmi Anda berhasil dikirim.'}</div>
                        <a href="{{ route('investor.dashboard') }}" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3">
                            <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard
                        </a>
                    </div>
                `;
                setTimeout(() => {
                    const modal = getMinatModalInstance();
                    if (modal) modal.hide();
                    form.reset();
                }, 3500);
            } else {
                alertBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0';
                alertBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + (data.message || 'Terjadi kesalahan pada formulir.');
            }
        })
        .catch(err => {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            submitBtn.disabled = false;
            alertBox.className = 'alert alert-danger py-2 px-3 small rounded-3 mb-0';
            alertBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal mengirim data. Silakan coba lagi.';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('jenisPotensiFilter').value = '';
        document.getElementById('kecamatanFilter').value = '';
        document.getElementById('skalaFilter').value = '';
        document.getElementById('statusLahanFilter').value = '';
        document.getElementById('aksesJalanFilter').checked = false;
        document.getElementById('listrikFilter').checked = false;
        document.getElementById('airBersihFilter').checked = false;
        document.getElementById('internetFilter').checked = false;
        document.getElementById('sortBy').value = 'nama';
        document.getElementById('sortDirection').value = 'asc';
        loadPotensiInvestasis();
    }

    // --- FITUR BANDINGKAN PELUANG INVESTASI ---
    let selectedCompareIds = [];

    function toggleCompareItem(id) {
        id = parseInt(id);
        const idx = selectedCompareIds.indexOf(id);
        if (idx > -1) {
            selectedCompareIds.splice(idx, 1);
        } else {
            if (selectedCompareIds.length >= 3) {
                alert(i18n.compareMaxAlert);
                return;
            }
            selectedCompareIds.push(id);
        }
        updateCompareBar();
    }

    function updateCompareBar() {
        const bar = document.getElementById('compareFloatingBar');
        const countEl = document.getElementById('compareCount');
        if (!bar) return;

        if (selectedCompareIds.length > 0) {
            bar.classList.remove('d-none');
            bar.classList.add('d-flex');
            if (countEl) countEl.innerText = selectedCompareIds.length;
        } else {
            bar.classList.remove('d-flex');
            bar.classList.add('d-none');
        }
    }

    function clearCompare() {
        selectedCompareIds = [];
        updateCompareBar();
    }

    function goToCompare() {
        if (selectedCompareIds.length === 0) return;
        window.location.href = `/potensi/bandingkan?ids=${selectedCompareIds.join(',')}`;
    }

    function escapeQuotes(str) {
        if (!str) return '';
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function loadCuacaBahariMarkers() {
        if (!cuacaBahariLayer) return;

        const renderStations = (stList) => {
            cuacaBahariLayer.clearLayers();
            (stList || []).forEach(st => {
                let waveColor = '#10b981';
                if (st.status_code === 'warning') waveColor = '#f59e0b';
                if (st.status_code === 'danger') waveColor = '#ef4444';

                const waveIcon = L.divIcon({
                    className: 'custom-marine-marker',
                    html: `
                        <div style="
                            background: white;
                            border: 2.5px solid ${waveColor};
                            border-radius: 50%;
                            width: 36px;
                            height: 36px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                            cursor: pointer;
                        " title="${escapeHtml(st.nama)}">
                            <i class="bi bi-water" style="font-size: 1.15rem; color: ${waveColor};"></i>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -18]
                });

                const m = L.marker([st.latitude, st.longitude], { icon: waveIcon });
                m.bindPopup(`
                    <div style="font-family: inherit; font-size: 0.85rem; min-width: 250px; padding: 2px;">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-2">
                            <strong style="color: #0f172a;">${escapeHtml(st.nama)}</strong>
                            <span class="badge" style="background-color: ${waveColor}; color: white; font-size: 0.7rem;">${escapeHtml(st.kategori_gelombang)}</span>
                        </div>
                        <div class="mb-2 text-muted small"><i class="bi bi-geo-alt text-primary"></i> ${escapeHtml(st.lokasi)}</div>
                        <div style="background: #f8fafc; border-radius: 6px; padding: 8px; margin-bottom: 8px; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted">${i18n.waveHeight}:</span>
                                <strong style="color: ${waveColor};">${st.tinggi_gelombang}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted">${i18n.windSpeed}:</span>
                                <strong>${st.kecepatan_angin}</strong>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">${i18n.seaTemp}:</span>
                                <strong>${st.suhu_laut}</strong>
                            </div>
                        </div>
                        <div class="alert alert-light border small p-2 mb-2 text-dark rounded-2" style="font-size: 0.78rem;">
                            <strong>Status:</strong> ${st.status_keselamatan}
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">${st.rekomendasi}</div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary w-100 rounded-pill py-1" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#cuacaBahariModal">
                            <i class="bi bi-info-circle me-1"></i> ${i18n.viewBmkgBulletin}
                        </button>
                    </div>
                `);
                cuacaBahariLayer.addLayer(m);
            });
        };

        if (initialCuacaData && initialCuacaData.stasiun) {
            renderStations(initialCuacaData.stasiun);
        } else {
            fetch('/api/cuaca-bahari')
                .then(res => res.json())
                .then(data => renderStations(data.stasiun || []))
                .catch(e => console.log('Cuaca layer info:', e));
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // --- EXECUTIVE GIS CONTROL TOOLS ---
    function switchBasemap(type, btn) {
        if (!map || !currentBaseLayer) return;
        map.removeLayer(currentBaseLayer);

        if (type === 'satellite') {
            currentBaseLayer = satelliteLayer;
        } else if (type === 'dark') {
            currentBaseLayer = darkLayer;
        } else {
            currentBaseLayer = osmLayer;
        }

        currentBaseLayer.addTo(map);
        currentBaseLayer.bringToBack();

        document.querySelectorAll('.dock-btn[data-basemap]').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
    }

    function toggleMeasureTool(btn) {
        measureActive = !measureActive;
        const banner = document.getElementById('measureBanner');

        if (measureActive) {
            if (btn) btn.classList.add('active');
            if (banner) banner.classList.remove('d-none');
            map.getContainer().style.cursor = 'crosshair';
            measurePoints = [];
            measureLayerGroup.clearLayers();
        } else {
            if (btn) btn.classList.remove('active');
            if (banner) banner.classList.add('d-none');
            map.getContainer().style.cursor = '';
            measurePoints = [];
            measureLayerGroup.clearLayers();
        }
    }

    function resetMeasure() {
        measurePoints = [];
        measureLayerGroup.clearLayers();
    }

    function toggleFullScreen() {
        const fsIcon = document.getElementById('fsIcon');
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
            if (fsIcon) fsIcon.className = 'bi bi-fullscreen-exit text-warning';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            if (fsIcon) fsIcon.className = 'bi bi-fullscreen text-info';
        }
    }

    function resetSangiheView() {
        if (map) {
            map.flyTo([3.58, 125.58], 11, {
                duration: 1.4,
                easeLinearity: 0.25
            });
        }
    }

    function animateCountUp(elementId, targetValue, isCurrency, duration = 1400) {
        const el = document.getElementById(elementId);
        if (!el) return;

        let startTime = null;
        const isEn = i18n.isEn;
        const unitSuffix = isEn ? ' B' : ' M';
        const decSep = isEn ? '.' : ',';

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const currentVal = progress * targetValue;

            if (isCurrency) {
                el.innerText = 'Rp ' + currentVal.toFixed(1).replace('.', decSep) + unitSuffix;
            } else {
                el.innerText = Math.floor(currentVal);
            }

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                if (isCurrency) {
                    el.innerText = 'Rp ' + targetValue.toFixed(1).replace('.', decSep) + unitSuffix;
                } else {
                    el.innerText = targetValue;
                }
            }
        }
        window.requestAnimationFrame(step);
    }
</script>
@endpush
