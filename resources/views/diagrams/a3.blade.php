<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gambar A.3. Kerangka Logis Indikator Keberhasilan</title>
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
        .tier-box {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 16px;
            position: relative;
        }
        .tier-header {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .item-pill {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #e2e8f0;
            height: 100%;
        }
        .tier-arrow {
            text-align: center;
            color: #38bdf8;
            font-size: 1.6rem;
            margin-top: -8px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="canvas-card">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <div>
                <span class="header-badge"><i class="bi bi-bullseye me-1"></i> LOGICAL FRAMEWORK ANALYSIS</span>
                <h1 class="title-main">Gambar A.3. Kerangka Logis Indikator Keberhasilan (Output, Outcome, Benefit)</h1>
                <p class="text-white-50 mb-0">Rantai Manfaat dan Akuntabilitas Penyelenggaraan Inovasi SIPOTENSI SANGIHE</p>
            </div>
            <div>
                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-graph-up me-1"></i> Dampak Terukur
                </span>
            </div>
        </div>

        <!-- Tier 1: OUTPUT -->
        <div class="tier-box" style="border-left: 6px solid #3b82f6;">
            <div class="tier-header text-primary">
                <i class="bi bi-box-seam-fill fs-4"></i>
                <span>1. TINGKAT KELUARAN NYATA (OUTPUT)</span>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-map-fill text-info me-1"></i> 1 Aplikasi WebGIS Terpadu:</strong> 15 kecamatan, batas 167 desa/kelurahan, dan 29 titik peluang investasi tervalidasi.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-box-fill text-warning me-1"></i> Fitur 3D & Finansial Pintar:</strong> Model 3D Digital Twin WebGL, Kalkulator Simulasi ROI/BEP, dan Matriks Komparasi.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i> Layanan Investasi Digital:</strong> Generator PDF Prospektus Resmi A4, Formulir e-LoI BKPM, & integrasi cuaca BMKG.
                    </div>
                </div>
            </div>
        </div>

        <div class="tier-arrow"><i class="bi bi-arrow-down-circle-fill"></i></div>

        <!-- Tier 2: OUTCOME -->
        <div class="tier-box" style="border-left: 6px solid #06b6d4;">
            <div class="tier-header text-info">
                <i class="bi bi-lightning-charge-fill fs-4"></i>
                <span>2. TINGKAT DAMPAK STRATEGIS (OUTCOME)</span>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-stopwatch-fill text-success me-1"></i> Efisiensi Biaya & Waktu >85%:</strong> Investor dapat melakukan studi kelayakan awal (pre-feasibility) secara instan tanpa survei fisik berulang.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-database-check text-primary me-1"></i> Satu Data (Single Source of Truth):</strong> Integrasi data spasial dengan pola ruang RTRW daerah, menjamin kepastian hukum lahan.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-globe2 text-info me-1"></i> Jangkauan Global 24/7:</strong> Menembus batas geografis pulau terluar, membuka akses informasi penanaman modal ke pasar internasional.
                    </div>
                </div>
            </div>
        </div>

        <div class="tier-arrow"><i class="bi bi-arrow-down-circle-fill"></i></div>

        <!-- Tier 3: BENEFIT -->
        <div class="tier-box" style="border-left: 6px solid #22c55e;">
            <div class="tier-header text-success">
                <i class="bi bi-trophy-fill fs-4"></i>
                <span>3. TINGKAT KEMANFAATAN PEMBANGUNAN (BENEFIT)</span>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-cash-stack text-warning me-1"></i> Peningkatan Realisasi Investasi:</strong> Percepatan masuknya modal PMDN dan PMA sektor kelautan, pariwisata, pertanian, & energi.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-people-fill text-info me-1"></i> Penyerapan Tenaga Kerja Lokal:</strong> Terbukanya sentra hilirisasi perikanan tuna, budidaya rumput laut, dan pengolahan pala rakyat.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item-pill">
                        <strong><i class="bi bi-graph-up-arrow text-success me-1"></i> Kemandirian Fiskal & PAD Daerah:</strong> Peningkatan penerimaan daerah untuk pembangunan infrastruktur berkelanjutan di perbatasan NKRI.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>