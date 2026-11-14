<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gambar A.1. Peta Geostrategis Kabupaten Kepulauan Sangihe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #070c18;
            color: #f1f5f9;
            margin: 0;
            padding: 30px;
        }
        .canvas-card {
            background: radial-gradient(circle at 50% 10%, #172554 0%, #090e1c 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        .header-badge {
            background: rgba(37, 99, 235, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.4);
            color: #60a5fa;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-block;
        }
        .title-main {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 10px;
            margin-bottom: 4px;
        }
        .sub-main {
            color: #94a3b8;
            font-size: 0.95rem;
        }
        .map-box {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 16px;
            padding: 20px;
            position: relative;
        }
        .stat-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 12px;
            padding: 14px 18px;
            height: 100%;
        }
        .stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #38bdf8;
        }
        .stat-lbl {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="canvas-card">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary border-opacity-25 pb-3">
            <div>
                <span class="header-badge"><i class="bi bi-geo-alt-fill me-1"></i> ANALISIS GEOSTRATEGIS KAWASAN</span>
                <h1 class="title-main">Gambar A.1. Peta Geostrategis Kabupaten Kepulauan Sangihe</h1>
                <p class="sub-main mb-0">Posisi Kunci Beranda Terdepan NKRI di Kawasan Perbatasan Pasifik & Alur Laut Kepulauan Indonesia (ALKI II)</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill"><i class="bi bi-shield-lock-fill me-1"></i> WPPNRI 716 / PKSN Tahuna</span>
            </div>
        </div>

        <div class="row g-4 align-items-center">
            <!-- SVG Map Illustration -->
            <div class="col-lg-8">
                <div class="map-box text-center">
                    <svg viewBox="0 0 850 480" width="100%" height="auto" style="border-radius: 10px;">
                        <!-- Background Grid -->
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                            </pattern>
                            <linearGradient id="oceanGrad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#0b192e"/>
                                <stop offset="100%" stop-color="#020817"/>
                            </linearGradient>
                        </defs>
                        <rect width="850" height="480" fill="url(#oceanGrad)"/>
                        <rect width="850" height="480" fill="url(#grid)"/>

                        <!-- Coordinates -->
                        <text x="30" y="40" fill="#64748b" font-size="12" font-family="monospace">5°00' LU</text>
                        <text x="30" y="240" fill="#64748b" font-size="12" font-family="monospace">3°30' LU (Sangihe)</text>
                        <text x="30" y="440" fill="#64748b" font-size="12" font-family="monospace">1°30' LU (Manado)</text>
                        <text x="400" y="465" fill="#64748b" font-size="12" font-family="monospace">125°30' BT</text>

                        <!-- Laut Sulawesi & Samudera Pasifik -->
                        <text x="120" y="180" fill="rgba(56, 189, 248, 0.25)" font-size="28" font-weight="900" letter-spacing="8">LAUT SULAWESI</text>
                        <text x="560" y="280" fill="rgba(96, 165, 250, 0.25)" font-size="28" font-weight="900" letter-spacing="8">SAMUDERA PASIFIK</text>

                        <!-- Philippines / Mindanao Mainland (North) -->
                        <path d="M 280,20 Q 380,30 480,20 L 520,70 Q 420,80 340,70 Z" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                        <circle cx="410" cy="50" r="6" fill="#ef4444"/>
                        <text x="425" y="55" fill="#f87171" font-size="13" font-weight="bold">Davao / General Santos (Filipina)</text>
                        <text x="425" y="70" fill="#94a3b8" font-size="10">Pusat Ekonomi BIMP-EAGA</text>

                        <!-- Pulau Marore & Miangas (Border Islands) -->
                        <circle cx="415" cy="115" r="5" fill="#fbbf24"/>
                        <text x="428" y="119" fill="#fde047" font-size="11" font-weight="bold">P. Marore (Perbatasan RI-PH)</text>

                        <!-- Trade Route Lines (Dotted Glowing) -->
                        <path d="M 410,55 L 415,220" stroke="#f59e0b" stroke-width="2" stroke-dasharray="6,6"/>
                        <text x="425" y="170" fill="#fbbf24" font-size="11" font-weight="bold">Koridor Logistik 140 NM</text>

                        <!-- ALKI II Shipping Lane -->
                        <path d="M 270,30 L 350,470" stroke="#06b6d4" stroke-width="3" stroke-dasharray="10,6" opacity="0.8"/>
                        <text x="210" y="320" fill="#22d3ee" font-size="12" font-weight="bold" transform="rotate(78 210,320)">ALUR LAUT KEPULAUAN INDONESIA (ALKI II)</text>

                        <!-- Pulau Sangihe Besar (Mainland) -->
                        <g transform="translate(385, 190) scale(1.1)">
                            <!-- Island Shape -->
                            <path d="M 30,10 Q 55,20 60,45 Q 65,75 50,110 Q 40,140 25,160 Q 15,130 18,90 Q 20,50 30,10 Z" fill="#15803d" stroke="#22c55e" stroke-width="2.5" filter="drop-shadow(0 0 12px rgba(34, 197, 94, 0.6))"/>
                            <!-- Tahuna Capital Pin -->
                            <circle cx="36" cy="72" r="7" fill="#ef4444" stroke="#ffffff" stroke-width="2"/>
                            <text x="50" y="75" fill="#ffffff" font-size="13" font-weight="900">TAHUNA (Ibukota)</text>
                            <text x="50" y="90" fill="#86efac" font-size="10">Pusat Pemerintahan & Pelabuhan</text>
                            <!-- Tamako & Dagho Port -->
                            <circle cx="30" cy="118" r="5" fill="#3b82f6" stroke="#ffffff" stroke-width="1.5"/>
                            <text x="44" y="122" fill="#93c5fd" font-size="11" font-weight="bold">Pelabuhan Perikanan Dagho</text>
                        </g>

                        <!-- Kepulauan Sitaro & Sulawesi Utara (South) -->
                        <ellipse cx="410" cy="410" rx="18" ry="12" fill="#1e293b" stroke="#334155" stroke-width="1.5"/>
                        <text x="435" y="415" fill="#cbd5e1" font-size="11">Kepulauan Sitaro</text>
                        <path d="M 320,460 Q 420,445 470,470" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                        <text x="490" y="465" fill="#cbd5e1" font-size="12" font-weight="bold">Daratan Manado / Bitung (KTI)</text>
                    </svg>
                </div>
            </div>

            <!-- Strategic Indicators -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3">
                    <div class="stat-card">
                        <div class="stat-lbl"><i class="bi bi-compass-fill text-info me-1"></i> Posisi Geografis Kunci</div>
                        <div class="stat-val">3°22' – 4°30' LU</div>
                        <small class="text-white-50">Berbatasan maritim langsung dengan Laut Mindanao, Filipina Selatan</small>
                    </div>

                    <div class="stat-card">
                        <div class="stat-lbl"><i class="bi bi-ship-fill text-warning me-1"></i> Keunggulan ALKI II</div>
                        <div class="stat-val">Koridor Pasifik</div>
                        <small class="text-white-50">Lintasan perdagangan internasional menghubungkan Australia, Selat Lombok, ke Asia Timur</small>
                    </div>

                    <div class="stat-card">
                        <div class="stat-lbl"><i class="bi bi-layers-half text-success me-1"></i> Potensi Sumber Daya Laut</div>
                        <div class="stat-val">WPPNRI 716</div>
                        <small class="text-white-50">Kawasan pengelolaan perikanan kaya tuna sirip kuning, cakalang, dan rumput laut pasifik</small>
                    </div>

                    <div class="stat-card">
                        <div class="stat-lbl"><i class="bi bi-buildings-fill text-primary me-1"></i> Pintu Gerbang Kawasan</div>
                        <div class="stat-val">PKSN Tahuna</div>
                        <small class="text-white-50">Pusat Kawasan Strategis Nasional beranda terdepan investasi terpadu Indonesia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>