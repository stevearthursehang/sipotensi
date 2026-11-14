<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DOKUMEN PROSPEKTUS INVESTASI KABUPATEN KEPULAUAN SANGIHE - {{ $potensi->nama }}</title>
    <style>
        @page {
            margin: 12mm 14mm 14mm 14mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 8.5pt;
            line-height: 1.38;
            margin: 0;
            padding: 0;
        }

        /* KOP SURAT RESMI PEMERINTAH DAERAH */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-logo-cell {
            width: 78pt;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo-img {
            width: 72pt;
            height: 70.7pt;
            display: inline-block;
        }
        .kop-text-cell {
            text-align: center;
            vertical-align: middle;
            padding: 0 4px;
        }
        .kop-spacer-cell {
            width: 78pt; /* Spacer simetris penyeimbang logo agar teks kop 100% presisi di tengah halaman */
        }
        .kop-instansi-1 {
            font-size: 11.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 2px 0;
        }
        .kop-instansi-2 {
            font-size: 10.5pt;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.25;
            margin: 0 0 3px 0;
        }
        .kop-alamat {
            font-size: 7.4pt;
            color: #334155;
            margin: 0 0 1.5px 0;
            line-height: 1.3;
        }
        .kop-kontak {
            font-size: 7.1pt;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }
        .garis-kop-tebal {
            border-top: 2.5px solid #0f172a;
            margin-top: 5px;
            margin-bottom: 1.5px;
        }
        .garis-kop-tipis {
            border-top: 1px solid #0f172a;
            margin-bottom: 8px;
        }

        /* HEADER DOKUMEN PROSPEKTUS */
        .prospektus-box {
            background-color: #f8fafc;
            border: 1.5px solid #1e3a8a;
            border-left: 5px solid #1e3a8a;
            border-radius: 4px;
            padding: 7px 12px;
            margin-bottom: 7px;
        }
        .prospektus-title-main {
            font-size: 11.5pt;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .prospektus-title-region {
            font-size: 9.8pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
            margin-bottom: 3px;
        }
        .prospektus-potensi-name {
            font-size: 11.5pt;
            font-weight: 800;
            color: #0284c7;
            margin-bottom: 4px;
        }
        .prospektus-meta-table {
            width: 100%;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
            font-size: 7.6pt;
        }
        .prospektus-meta-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        /* SECTION TITLE */
        .section-heading {
            font-size: 8.8pt;
            font-weight: bold;
            color: #1e3a8a;
            background-color: #f1f5f9;
            border-left: 3.5px solid #2563eb;
            padding: 3px 6px;
            margin-top: 6px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* METRIC CARDS */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 5px;
        }
        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-top: 3px solid #1e3a8a;
            border-radius: 4px;
            padding: 4px 6px;
            text-align: center;
        }
        .kpi-card .kpi-lbl {
            font-size: 6.6pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .kpi-card .kpi-val {
            font-size: 9.8pt;
            font-weight: 800;
            color: #1e3a8a;
            margin-top: 1px;
        }

        /* DATA TABLE */
        table.spec-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 8.1pt;
        }
        table.spec-table th, table.spec-table td {
            padding: 3.5px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        table.spec-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: bold;
            text-align: left;
            width: 32%;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            font-size: 7.2pt;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-info { background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }

        /* FOTO DOKUMENTASI */
        .photo-container {
            width: 100%;
            text-align: center;
            margin-top: 3px;
            margin-bottom: 5px;
        }
        .photo-box {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 3px;
            background: #ffffff;
            border-radius: 4px;
        }
        .photo-img {
            max-width: 100%;
            max-height: 110px;
            border-radius: 2px;
        }
        .photo-caption {
            font-size: 7pt;
            color: #64748b;
            margin-top: 2px;
            font-style: italic;
        }

        /* DESKRIPSI */
        .desc-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #cbd5e1;
            padding: 6px 10px;
            font-size: 8pt;
            color: #334155;
            line-height: 1.45;
            text-align: justify;
            margin-bottom: 7px;
        }

        /* RUNNING HEADER FOR PAGE 2 */
        .running-header {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.2pt;
            color: #64748b;
            margin-bottom: 2px;
        }
        .running-header td {
            padding: 0;
            border: none;
        }

        /* FOOTER & PENGESAHAN */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8pt;
        }
        .footer-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .verification-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 8px;
            background-color: #f8fafc;
            font-size: 7pt;
            color: #64748b;
            line-height: 1.35;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- DOMPDF PAGE NUMBERING SCRIPT -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}  •  Portal Resmi GIS Investasi Kabupaten Kepulauan Sangihe (SIPOTENSI)";
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 7.2;
            $color = array(0.45, 0.5, 0.58);
            $y = $pdf->get_height() - 24;
            $x = ($pdf->get_width() - $fontMetrics->get_text_width($text, $font, $size)) / 2;
            $pdf->text($x, $y, $text, $font, $size, $color);
        }
    </script>

    @php
        // Prepare Official Logo Daerah Sangihe (Coat of Arms: SOMAHE KAI KEHAGE)
        $logoPath = public_path('images/logo_sangihe.jpg');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/logo.png');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
            $mime = ($ext === 'jpg' || $ext === 'jpeg') ? 'image/jpeg' : 'image/png';
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Prepare Foto Utama if available
        $fotoBase64 = '';
        if (!empty($potensi->foto_utama)) {
            $fullFotoPath = public_path('storage/' . $potensi->foto_utama);
            if (file_exists($fullFotoPath)) {
                $ext = pathinfo($fullFotoPath, PATHINFO_EXTENSION);
                $fotoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($fullFotoPath));
            }
        }

        $nilaiInv = $potensi->nilai_estimasi_investasi ?? $potensi->nilai_estimasi ?? 0;
        $roiVal = $potensi->roi_estimasi ?? 0;
        $paybackYrs = $roiVal > 0 ? round(100 / $roiVal, 1) : null;
    @endphp

    <!-- ========================================== -->
    <!-- HALAMAN 1: EKSEKUTIF PROSPEKTUS INVESTASI   -->
    <!-- ========================================== -->

    <!-- KOP RESMI PEMERINTAH DAERAH KABUPATEN KEPULAUAN SANGIHE -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="kop-logo-img" alt="Lambang Kabupaten Kepulauan Sangihe">
                @endif
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi-1">PEMERINTAH KABUPATEN KEPULAUAN SANGIHE</div>
                <div class="kop-instansi-2">DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU<br>SATU PINTU (DPMPTSP)</div>
                <div class="kop-alamat">Jl. Malahasa, Kelurahan Soataloara II, Kecamatan Tahuna, Kabupaten Kepulauan Sangihe, Sulawesi Utara</div>
                <div class="kop-kontak">Telp. (0432) 22541 &bull; Email: dpmptspsangihe@gmail.com &bull; Instagram Resmi: @dpmptspdsangihekab</div>
            </td>
            <td class="kop-spacer-cell">
                <!-- Spacer simetris penyeimbang logo agar teks kop 100% presisi di tengah halaman -->
            </td>
        </tr>
    </table>
    <div class="garis-kop-tebal"></div>
    <div class="garis-kop-tipis"></div>

    <!-- BLOK JUDUL DOKUMEN PROSPEKTUS INVESTASI RESMI -->
    <div class="prospektus-box">
        <div class="prospektus-title-main">DOKUMEN PROSPEKTUS INVESTASI</div>
        <div class="prospektus-title-region">KABUPATEN KEPULAUAN SANGIHE</div>
        <div class="prospektus-potensi-name">{{ $potensi->nama }}</div>

        <table class="prospektus-meta-table">
            <tr>
                <td style="width: 62%;">
                    <span style="font-size: 7pt; text-transform: uppercase; color: #64748b; font-weight: bold;">Diterbitkan Oleh:</span><br>
                    <strong style="color: #0f172a; font-size: 8.3pt;">Dinas Penanaman Modal dan Pelayanan Terpadu<br>Satu Pintu (DPMPTSP)</strong><br>
                    <span style="color: #1e3a8a; font-weight: 600;">Pemerintah Kabupaten Kepulauan Sangihe</span>
                </td>
                <td style="width: 38%; text-align: right;">
                    <strong>No. Registrasi:</strong> <span style="font-family: monospace; font-weight: bold; color: #1e3a8a;">SGH-INV-{{ str_pad($potensi->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                    <strong>Sektor Utama:</strong> {{ $potensi->jenis_potensi ?? 'Peluang Unggulan' }}<br>
                    <strong>Tahun Rilis:</strong> {{ date('Y') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- METRIK KELAYAKAN FINANSIAL & SKALA INVESTASI -->
    <table class="kpi-table">
        <tr>
            <td style="width: 25%;" class="kpi-card">
                <div class="kpi-lbl">Estimasi Nilai Investasi (CAPEX)</div>
                <div class="kpi-val" style="color: #166534;">Rp {{ number_format($nilaiInv, 0, ',', '.') }}</div>
            </td>
            <td style="width: 25%;" class="kpi-card">
                <div class="kpi-lbl">Perkiraan ROI (Tahunan)</div>
                <div class="kpi-val" style="color: #2563eb;">{{ $roiVal ? number_format($roiVal, 1, ',', '.') . '%' : 'Prospektif' }}</div>
            </td>
            <td style="width: 25%;" class="kpi-card">
                <div class="kpi-lbl">Perkiraan Balik Modal (BEP)</div>
                <div class="kpi-val" style="color: #d97706;">{{ $paybackYrs ? $paybackYrs . ' Tahun' : 'Komparatif' }}</div>
            </td>
            <td style="width: 25%;" class="kpi-card">
                <div class="kpi-lbl">Skala Peluang Usaha</div>
                <div class="kpi-val" style="text-transform: capitalize; color: #0f172a;">{{ $potensi->skala_peluang ?? 'Menengah' }}</div>
            </td>
        </tr>
    </table>

    <!-- FOTO DOKUMENTASI LAPANGAN (JIKA ADA) -->
    @if(!empty($fotoBase64))
    <div class="photo-container">
        <div class="photo-box">
            <img src="{{ $fotoBase64 }}" class="photo-img" alt="{{ $potensi->nama }}">
            <div class="photo-caption">Dokumentasi Lokasi Peluang Investasi: {{ $potensi->nama }} (Foto Resmi Lapangan DPMPTSP)</div>
        </div>
    </div>
    @endif

    <!-- I. IDENTITAS PROYEK & WILAYAH SPASIAL -->
    <div class="section-heading">I. Profil Geografis & Administrasi Wilayah</div>
    <table class="spec-table">
        <tr>
            <th>Kabupaten / Daerah</th>
            <td>Kabupaten Kepulauan Sangihe, Provinsi Sulawesi Utara</td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><strong>{{ $potensi->kelurahan?->kecamatan ?? '-' }}</strong></td>
        </tr>
        <tr>
            <th>Kampung / Kelurahan</th>
            <td><strong>{{ $potensi->kelurahan?->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <th>Alamat Lokasi Rinci</th>
            <td>{{ $potensi->alamat ?: ('Wilayah Kampung ' . ($potensi->kelurahan?->nama ?? '') . ', Kecamatan ' . ($potensi->kelurahan?->kecamatan ?? '')) }}</td>
        </tr>
        <tr>
            <th>Georeferensi Spasial (WGS84)</th>
            <td>
                Latitude (Lintang): <strong>{{ $potensi->latitude ?? '-' }}</strong> &bull; 
                Longitude (Bujur): <strong>{{ $potensi->longitude ?? '-' }}</strong>
            </td>
        </tr>
        <tr>
            <th>Luas Wilayah Kampung</th>
            <td>{{ $potensi->kelurahan?->luas_wilayah ? number_format($potensi->kelurahan->luas_wilayah, 2, ',', '.') . ' Hektar (Ha)' : '-' }}</td>
        </tr>
    </table>

    <!-- II. STATUS KESIAPAN LAHAN & LEGALITAS -->
    <div class="section-heading">II. Kesiapan Lahan & Kepastian Hukum (Clean & Clear)</div>
    <table class="spec-table">
        <tr>
            <th>Status Kesiapan Lahan</th>
            <td>
                @if($potensi->status_lahan === 'tersedia')
                    <span class="badge badge-success">Lahan Siap Bangun / Clear & Clean (Tersedia Penuh)</span>
                @elseif($potensi->status_lahan === 'dalam_proses')
                    <span class="badge badge-warning">Dalam Proses Fasilitasi Pembebasan / Penataan</span>
                @else
                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $potensi->status_lahan ?? 'Tersedia')) }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Status Perizinan Dasar (OSS-RBA)</th>
            <td>
                @if($potensi->status_perizinan === 'sudah_izin' || $potensi->status_perizinan === 'sudah')
                    <span class="badge badge-success">Izin Dasar Terpenuhi / Siap Registrasi OSS-RBA BKPM</span>
                @else
                    <span class="badge badge-warning">Pendampingan Perizinan Terpadu Prioritas oleh DPMPTSP</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Fasilitasi & Insentif Daerah</th>
            <td>Keringanan retribusi daerah, fasilitasi percepatan izin OSS-RBA, dan jaminan kepastian investasi daerah.</td>
        </tr>
    </table>

    <!-- ========================================== -->
    <!-- HALAMAN 2: UTILITAS, ANALISIS & PENGESAHAN -->
    <!-- ========================================== -->
    <div class="page-break"></div>

    <!-- RUNNING HEADER HALAMAN 2 -->
    <table class="running-header">
        <tr>
            <td style="text-align: left;">
                <strong>DOKUMEN PROSPEKTUS INVESTASI</strong> &bull; KABUPATEN KEPULAUAN SANGIHE
            </td>
            <td style="text-align: right; color: #1e3a8a; font-weight: bold;">
                {{ $potensi->nama }} (No. SGH-INV-{{ str_pad($potensi->id, 4, '0', STR_PAD_LEFT) }})
            </td>
        </tr>
    </table>
    <div style="border-top: 1.5px solid #1e3a8a; margin-bottom: 7px;"></div>

    <!-- III. AKSESIBILITAS & UTILITAS INFRASTRUKTUR -->
    <div class="section-heading">III. Utilitas Infrastruktur & Konektivitas Logistik</div>
    <table class="spec-table">
        <tr>
            <th>Akses Jalan Transportasi</th>
            <td>{{ ($potensi->akses_jalan ?? $potensi->kelurahan?->akses_jalan) ? 'Tersedia (Akses Jalan Aspal / Rabat Beton Baik, Dapat Dilalui Kendaraan Roda 4)' : 'Jalan Rintisan / Pengerasan' }}</td>
        </tr>
        <tr>
            <th>Jaringan Listrik PLN</th>
            <td>{{ ($potensi->listrik ?? $potensi->kelurahan?->listrik) ? 'Tersedia (Terkoneksi Jaringan PLN 24 Jam)' : 'Belum Terkoneksi' }}</td>
        </tr>
        <tr>
            <th>Pasokan Air Bersih</th>
            <td>{{ ($potensi->air_bersih ?? $potensi->kelurahan?->air_bersih) ? 'Tersedia (Jaringan PDAM / Sumber Mata Air Pegunungan Alami)' : 'Swadaya / Sumur Bor' }}</td>
        </tr>
        <tr>
            <th>Jaringan Telekomunikasi & 4G</th>
            <td>{{ ($potensi->internet ?? $potensi->kelurahan?->internet) ? 'Tersedia (Jaringan Seluler 4G LTE & Akses Fiber Optic)' : 'Jaringan Seluler Terbatas' }}</td>
        </tr>
        <tr>
            <th>Jarak ke Pelabuhan Laut</th>
            <td>{{ $potensi->jarak_pelabuhan ? $potensi->jarak_pelabuhan . ' Km (Akses Pelayaran & Logistik Kargo Antarpulau)' : 'Akses Pelabuhan Terdekat' }}</td>
        </tr>
        <tr>
            <th>Jarak ke Bandara Naha Tahuna</th>
            <td>{{ $potensi->jarak_bandara ? $potensi->jarak_bandara . ' Km (Akses Transportasi Udara Sangihe - Manado)' : 'Akses Penerbangan Domestik' }}</td>
        </tr>
    </table>

    <!-- IV. DEMOGRAFI & KETENAGAKERJAAN -->
    @if($potensi->kelurahan)
    <div class="section-heading">IV. Dukungan Tenaga Kerja & Demografi Lokal</div>
    <table class="spec-table">
        <tr>
            <th>Jumlah Penduduk Lokal</th>
            <td>{{ $potensi->kelurahan->jumlah_penduduk ? number_format($potensi->kelurahan->jumlah_penduduk, 0, ',', '.') . ' Jiwa' : '-' }}</td>
        </tr>
        <tr>
            <th>Tenaga Kerja Produktif Tersedia</th>
            <td>{{ $potensi->kelurahan->tenaga_kerja_tersedia ? number_format($potensi->kelurahan->tenaga_kerja_tersedia, 0, ',', '.') . ' Orang' : '-' }} (Dukungan SDM Siap Kerja)</td>
        </tr>
    </table>
    @endif

    <!-- V. DESKRIPSI PELUANG INVESTASI -->
    <div class="section-heading">V. Deskripsi Rencana & Prospek Pasar</div>
    <div class="desc-box">
        {!! nl2br(e($potensi->deskripsi ?: 'Peluang investasi strategis berbasis potensi keunggulan lokal di wilayah ' . ($potensi->kelurahan?->nama ?? 'Kepulauan Sangihe') . ' dengan dukungan penuh pemerintah daerah dan kesiapan sumber daya alam yang melimpah.')) !!}
    </div>

    <!-- TANDA TANGAN & PENGESAHAN RESMI DPMPTSP -->
    <table class="footer-table">
        <tr>
            <td style="width: 52%; padding-right: 12px;">
                <div class="verification-card">
                    <strong style="color: #0f172a; font-size: 7.5pt;">Validasi Dokumen Digital:</strong><br>
                    Dokumen ini diterbitkan secara resmi melalui Sistem Informasi Geografis (GIS) Penanaman Modal Pemerintah Kabupaten Kepulauan Sangihe (SIPOTENSI SANGIHE).
                    <div style="margin-top: 4px; font-family: monospace; font-size: 7.2pt; color: #1e3a8a;">
                        REG: DPMPTSP-SGH-INV-{{ date('Y') }}-{{ str_pad($potensi->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                    <div style="margin-top: 2px; font-size: 6.8pt; color: #94a3b8;">
                        Terverifikasi Sistem Elektronik DPMPTSP Kabupaten Kepulauan Sangihe
                    </div>
                </div>
            </td>
            <td style="width: 48%; text-align: center;">
                <p style="margin: 0; font-size: 8pt; color: #475569;">Tahuna, {{ date('d F Y') }}</p>
                <p style="margin: 2px 0 0 0; font-size: 7.2pt; text-transform: uppercase; color: #64748b; font-weight: bold;">Diterbitkan Oleh:</p>
                <p style="margin: 2px 0 35px 0; font-weight: 800; font-size: 8.2pt; line-height: 1.25; color: #0f172a;">
                    DINAS PENANAMAN MODAL DAN PELAYANAN<br>
                    TERPADU SATU PINTU (DPMPTSP)<br>
                    PEMERINTAH KABUPATEN KEPULAUAN SANGIHE
                </p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 8.8pt; color: #0f172a;">
                    BIDANG PENANAMAN MODAL
                </p>
                <p style="margin: 1px 0 0 0; font-size: 7.2pt; color: #64748b;">
                    Pemerintah Kabupaten Kepulauan Sangihe
                </p>
            </td>
        </tr>
    </table>

</body>
</html>