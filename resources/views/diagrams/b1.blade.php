<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gambar B.1. Diagram Alir Proses Layanan Investasi Digital</title>
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
        .flow-step {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 14px;
            padding: 16px;
            height: 100%;
            text-align: center;
            position: relative;
        }
        .flow-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 12px;
        }
        .flow-step-num {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1px;
            color: #38bdf8;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .flow-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .flow-desc {
            font-size: 0.75rem;
            color: #94a3b8;
            line-height: 1.4;
            margin-bottom: 0;
        }
        .flow-arrow-cell {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 1.3rem;
            padding: 0 4px;
        }
        .role-badge-investor {
            background: #2563eb;
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .role-badge-gov {
            background: #10b981;
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 6px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="canvas-card">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary border-opacity-25 pb-3">
            <div>
                <span class="header-badge"><i class="bi bi-person-check-fill me-1"></i> STANDARD OPERATING PROCEDURE (SOP)</span>
                <h1 class="title-main">Gambar B.1. Diagram Alir Proses Layanan Investasi Digital (Pencarian s.d. e-LoI)</h1>
                <p class="text-white-50 mb-0">End-to-End Digital Investor Journey pada Ekosistem Pelayanan Terpadu Satu Pintu (DPMPTSP)</p>
            </div>
            <div>
                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-clock-history me-1"></i> Respon Maksimal: 1x24 Jam
                </span>
            </div>
        </div>

        <div class="row g-2 align-items-stretch">
            <!-- Step 1 -->
            <div class="col">
                <div class="flow-step">
                    <span class="role-badge-investor"><i class="bi bi-person me-1"></i> Calon Investor</span>
                    <div class="flow-icon bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="flow-step-num">Langkah 01</div>
                    <div class="flow-title">Akses & Pencarian</div>
                    <p class="flow-desc">Investor mengakses `/map`, memfilter komoditas, infrastruktur, atau skala modal.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col">
                <div class="flow-step">
                    <span class="role-badge-investor"><i class="bi bi-person me-1"></i> Calon Investor</span>
                    <div class="flow-icon bg-info bg-opacity-25 text-info border border-info border-opacity-50">
                        <i class="bi bi-box-fill"></i>
                    </div>
                    <div class="flow-step-num">Langkah 02</div>
                    <div class="flow-title">Eksplorasi Spasial 3D</div>
                    <p class="flow-desc">Eksplorasi kontur topografi, citra satelit, zonasi RTRW, dan pantauan cuaca BMKG.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col">
                <div class="flow-step">
                    <span class="role-badge-investor"><i class="bi bi-person me-1"></i> Calon Investor</span>
                    <div class="flow-icon bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                    <div class="flow-step-num">Langkah 03</div>
                    <div class="flow-title">Simulasi Finansial ROI</div>
                    <p class="flow-desc">Memodelkan CAPEX, estimasi laba, titik impas (BEP), dan komparasi head-to-head.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col">
                <div class="flow-step">
                    <span class="role-badge-investor"><i class="bi bi-person me-1"></i> Calon Investor</span>
                    <div class="flow-icon bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div class="flow-step-num">Langkah 04</div>
                    <div class="flow-title">Unduh Prospektus</div>
                    <p class="flow-desc">Mengunduh Dokumen Prospektus Investasi Resmi PDF format A4 untuk bahan rapat direksi.</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="col">
                <div class="flow-step" style="border-color: #38bdf8;">
                    <span class="role-badge-investor"><i class="bi bi-person me-1"></i> Calon Investor</span>
                    <div class="flow-icon bg-info text-dark">
                        <i class="bi bi-send-check-fill"></i>
                    </div>
                    <div class="flow-step-num">Langkah 05</div>
                    <div class="flow-title">Kirim e-LoI Resmi</div>
                    <p class="flow-desc">Daftar akun investor mandiri dan kirim formulir Letter of Intent standar BKPM RI.</p>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="col">
                <div class="flow-step" style="border-color: #22c55e;">
                    <span class="role-badge-gov"><i class="bi bi-building me-1"></i> DPMPTSP Daerah</span>
                    <div class="flow-icon bg-success text-white">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div class="flow-step-num text-success">Langkah 06</div>
                    <div class="flow-title text-success">Verifikasi & Fasilitasi</div>
                    <p class="flow-desc">Verifikasi data di CMS Filament, penjadwalan audiensi teknis, & pengawalan izin OSS-RBA.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>