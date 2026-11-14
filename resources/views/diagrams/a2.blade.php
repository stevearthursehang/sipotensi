<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gambar A.2. Diagram Alir Tahapan Penyelenggaraan Inovasi</title>
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
        .phase-step {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            position: relative;
            transition: transform 0.2s;
        }
        .phase-num {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
        .phase-date {
            font-size: 0.75rem;
            font-weight: 700;
            color: #38bdf8;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .phase-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .phase-desc {
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.45;
            margin-bottom: 0;
        }
        .arrow-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="canvas-card">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <div>
                <span class="header-badge"><i class="bi bi-diagram-3-fill me-1"></i> METODOLOGI PENYELENGGARAAN</span>
                <h1 class="title-main">Gambar A.2. Diagram Alir Tahapan Penyelenggaraan Inovasi SIPOTENSI SANGIHE</h1>
                <p class="text-white-50 mb-0">Roadmap 5 Fase Pengembangan Sistem Terpadu Penanaman Modal Berbasis Geospasial</p>
            </div>
            <div>
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> Status: Operasional Penuh 2026
                </span>
            </div>
        </div>

        <div class="row g-3">
            <!-- Phase 1 -->
            <div class="col">
                <div class="phase-step">
                    <div class="phase-num bg-primary text-white">I</div>
                    <div class="phase-date"><i class="bi bi-calendar-event me-1"></i> Mar – Apr 2024</div>
                    <div class="phase-title">Riset & Pengumpulan Data</div>
                    <p class="phase-desc">
                        • Identifikasi kendala promosi investasi daerah<br>
                        • Inventarisasi 29 potensi unggulan di 15 kecamatan<br>
                        • Survei verifikasi titik koordinat GPS di lapangan
                    </p>
                </div>
            </div>

            <!-- Phase 2 -->
            <div class="col">
                <div class="phase-step">
                    <div class="phase-num bg-info text-dark">II</div>
                    <div class="phase-date"><i class="bi bi-calendar-event me-1"></i> Mei – Jul 2024</div>
                    <div class="phase-title">Desain Basis Data PostGIS</div>
                    <p class="phase-desc">
                        • Standardisasi GeoJSON batas 167 desa/kelurahan<br>
                        • Topologi spasial PostgreSQL/PostGIS terpadu<br>
                        • Penyelarasan pola ruang RTRW Sangihe
                    </p>
                </div>
            </div>

            <!-- Phase 3 -->
            <div class="col">
                <div class="phase-step">
                    <div class="phase-num bg-warning text-dark">III</div>
                    <div class="phase-date"><i class="bi bi-calendar-event me-1"></i> Agu – Des 2024</div>
                    <div class="phase-title">Sistem WebGIS & Prospektus</div>
                    <p class="phase-desc">
                        • Arsitektur backend modern Laravel 11<br>
                        • Integrasi stasiun cuaca maritim BMKG Sangihe<br>
                        • Generator otomatis prospektus investasi PDF A4
                    </p>
                </div>
            </div>

            <!-- Phase 4 -->
            <div class="col">
                <div class="phase-step">
                    <div class="phase-num bg-danger text-white">IV</div>
                    <div class="phase-date"><i class="bi bi-calendar-event me-1"></i> Jan – Jun 2025</div>
                    <div class="phase-title">Uji Coba & Portal e-LoI</div>
                    <p class="phase-desc">
                        • Peluncuran portal akun investor mandiri<br>
                        • Digitalisasi formulir Letter of Intent (e-LoI)<br>
                        • Panel verifikasi permohonan admin Filament 3
                    </p>
                </div>
            </div>

            <!-- Phase 5 -->
            <div class="col">
                <div class="phase-step" style="border-color: #22c55e;">
                    <div class="phase-num bg-success text-white">V</div>
                    <div class="phase-date text-success"><i class="bi bi-stars me-1"></i> Tahun 2026 (Puncak)</div>
                    <div class="phase-title text-success">Fitur Juara (3D & Simulasi)</div>
                    <p class="phase-desc">
                        • Visualisasi 3D Digital Twin WebGL Three.js<br>
                        • Kalkulator Simulasi Finansial & Proyeksi ROI<br>
                        • Matriks Komparasi Head-to-Head & Dashboard Publik
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>