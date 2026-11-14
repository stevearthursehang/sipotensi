import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
    tcPr.append(tcMar)

def create_document():
    doc = Document()

    # Page Setup: A4, Margins
    for section in doc.sections:
        section.page_width = Inches(8.27)
        section.page_height = Inches(11.69)
        section.top_margin = Inches(0.9)
        section.bottom_margin = Inches(0.9)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(0.9)

        # Header & Footer
        header = section.header
        hp = header.paragraphs[0]
        hp.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        hrun = hp.add_run("SIPOTENSI SANGIHE — Lampiran Bukti Fisik & Visual Inovasi Daerah")
        hrun.font.name = "Calibri"
        hrun.font.size = Pt(8.5)
        hrun.font.color.rgb = RGBColor(120, 144, 156)

        footer = section.footer
        fp = footer.paragraphs[0]
        fp.alignment = WD_ALIGN_PARAGRAPH.CENTER
        frun = fp.add_run("Badan Perencanaan, Penelitian dan Pengembangan Daerah (BAPPERIDA) Kab. Kepl. Sangihe © 2026")
        frun.font.name = "Calibri"
        frun.font.size = Pt(8.5)
        frun.font.color.rgb = RGBColor(140, 150, 160)

    # Base Styles
    styles = doc.styles
    normal_style = styles['Normal']
    normal_style.font.name = 'Calibri'
    normal_style.font.size = Pt(10.5)
    normal_style.font.color.rgb = RGBColor(30, 41, 59)
    normal_style.paragraph_format.line_spacing = 1.15
    normal_style.paragraph_format.space_after = Pt(4)

    # ==========================
    # COVER / HEADER SECTION
    # ==========================
    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_inst = p_inst.add_run("PEMERINTAH KABUPATEN KEPULAUAN SANGIHE\nBADAN PERENCANAAN, PENELITIAN DAN PENGEMBANGAN DAERAH (BAPPERIDA)")
    r_inst.font.name = "Arial"
    r_inst.font.size = Pt(11)
    r_inst.font.bold = True
    r_inst.font.color.rgb = RGBColor(15, 23, 42)

    p_div = doc.add_paragraph()
    p_div.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_div = p_div.add_run("─────────────────────────────────────────────────────────────")
    r_div.font.color.rgb = RGBColor(37, 99, 235)
    r_div.font.size = Pt(10)

    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_title = p_title.add_run("LAMPIRAN BUKTI FISIK & DOKUMENTASI VISUAL\nSISTEM INFORMASI POTENSI INVESTASI TERPADU\n(SIPOTENSI SANGIHE)")
    r_title.font.name = "Arial"
    r_title.font.size = Pt(16)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(15, 41, 107)

    p_sub = doc.add_paragraph()
    p_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_sub = p_sub.add_run("Dokumen Pendukung Resmi Proposal Kompetisi Inovasi Daerah\nInnovative Government Award (IGA) Tahun 2026")
    r_sub.font.size = Pt(11)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(71, 85, 105)

    doc.add_paragraph() # Spacing

    # Intro Note Box
    t_intro = doc.add_table(rows=1, cols=1)
    t_intro.alignment = WD_TABLE_ALIGNMENT.CENTER
    t_intro.autofit = False
    c_intro = t_intro.rows[0].cells[0]
    c_intro.width = Inches(6.3)
    set_cell_background(c_intro, "F1F5F9")
    set_cell_margins(c_intro, top=140, bottom=140, left=180, right=180)
    p_in = c_intro.paragraphs[0]
    r_in = p_in.add_run("KETERANGAN RESMI DOKUMEN:\n")
    r_in.bold = True
    r_in.font.size = Pt(10)
    r_in.font.color.rgb = RGBColor(15, 23, 42)
    r_in_text = p_in.add_run(
        "Dokumen ini memuat seluruh bukti tangkapan layar antarmuka digital (high-resolution screenshots), "
        "diagram alir arsitektur metodologi, dan kerangka logis pencapaian indikator keberhasilan dari "
        "inovasi SIPOTENSI SANGIHE. Seluruh data dan gambar telah diverifikasi sesuai kondisi operasional "
        "sistem berbasis WebGIS, WebGL 3D Digital Twin, serta basis data relasional spasial PostGIS."
    )
    r_in_text.font.size = Pt(9.5)
    r_in_text.font.color.rgb = RGBColor(51, 65, 85)

    doc.add_paragraph()

    # Table of Figures Summary Table
    p_tbl_lbl = doc.add_paragraph()
    r_tbl_lbl = p_tbl_lbl.add_run("DAFTAR REKAPITULASI DOKUMENTASI GAMBAR (11 GAMBAR):")
    r_tbl_lbl.font.bold = True
    r_tbl_lbl.font.size = Pt(10.5)
    r_tbl_lbl.font.color.rgb = RGBColor(15, 41, 107)

    items_summary = [
        ("Gambar A.1", "Peta Geostrategis Sangihe di Kawasan Perbatasan Pasifik & ALKI II", "Bagian A - Deskripsi Inovasi"),
        ("Gambar A.2", "Diagram Alir 5 Tahapan Penyelenggaraan Inovasi SIPOTENSI SANGIHE", "Bagian A - Metodologi"),
        ("Gambar A.3", "Kerangka Logis Indikator Keberhasilan (Output, Outcome, Benefit)", "Bagian A - Logical Framework"),
        ("Gambar B.1", "Diagram Alir SOP Layanan Investasi Digital (Pencarian s.d. e-LoI)", "Bagian B - Analisis Kriteria"),
        ("Gambar L.1", "Antarmuka Peta Spasial Interaktif WebGIS Multi-Layer Sangihe", "Lampiran Aplikasi (/map)"),
        ("Gambar L.2", "Fitur Mode Perspektif & Visualisasi Model 3D Digital Twin WebGL", "Lampiran Aplikasi (/map?view=3d)"),
        ("Gambar L.3", "Kalkulator Simulasi Finansial & Proyeksi Arus Kas ROI Interaktif", "Lampiran Aplikasi (/potensi/1)"),
        ("Gambar L.4", "Matriks Komparasi Peluang Investasi Head-to-Head Multi-Kriteria", "Lampiran Aplikasi (/potensi/bandingkan)"),
        ("Gambar L.5", "Dashboard Statistik & Infografis Investasi Publik Terbuka", "Lampiran Aplikasi (/statistik)"),
        ("Gambar L.6", "Dokumen Prospektus Investasi Format PDF Resmi Siap Cetak (A4)", "Lampiran Aplikasi (/potensi/1/pdf)"),
        ("Gambar L.7", "Portal Pendaftaran Akun Investor & Formulir Digital e-LoI BKPM", "Lampiran Aplikasi (/investor/register)")
    ]

    table = doc.add_table(rows=1, cols=3)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    
    col_widths = [Inches(1.2), Inches(3.6), Inches(1.5)]
    hdr_cells = table.rows[0].cells
    hdr_titles = ["Kode Gambar", "Judul Dokumentasi Visual", "Kategori Dokumen"]
    for i, title in enumerate(hdr_titles):
        hdr_cells[i].width = col_widths[i]
        set_cell_background(hdr_cells[i], "1E3A8A")
        set_cell_margins(hdr_cells[i], top=100, bottom=100, left=120, right=120)
        p = hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i == 0 else WD_ALIGN_PARAGRAPH.LEFT
        run = p.add_run(title)
        run.bold = True
        run.font.size = Pt(9.5)
        run.font.color.rgb = RGBColor(255, 255, 255)

    for idx, (code, title, kat) in enumerate(items_summary):
        row = table.add_row()
        cells = row.cells
        bg_color = "F8FAFC" if idx % 2 == 0 else "FFFFFF"
        for i, val in enumerate([code, title, kat]):
            cells[i].width = col_widths[i]
            set_cell_background(cells[i], bg_color)
            set_cell_margins(cells[i], top=70, bottom=70, left=100, right=100)
            p = cells[i].paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if i == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(val)
            r.font.size = Pt(9)
            if i == 0:
                r.bold = True
                r.font.color.rgb = RGBColor(30, 58, 138)

    doc.add_page_break()

    # ==========================
    # 11 FIGURES DETAIL SECTION
    # ==========================
    figures_data = [
        {
            "code": "Gambar A.1",
            "title": "Peta Geostrategis Kabupaten Kepulauan Sangihe di Kawasan Perbatasan Pasifik & ALKI II",
            "image": "public/images/proposal/gambar_a1.png",
            "url": "http://127.0.0.1:8000/images/proposal/gambar_a1.png",
            "category": "Bagian A: Deskripsi Bentuk Inovasi Daerah",
            "desc": (
                "Gambar ini menyajikan posisi geostrategis Kabupaten Kepulauan Sangihe yang berada pada koordinat astronomis "
                "3°22' – 4°30' Lintang Utara dan 125°10' – 125°45' Bujur Timur. Sebagai salah satu beranda terdepan NKRI, "
                "Sangihe memiliki keunggulan batas maritim langsung dengan Laut Mindanao, Filipina Selatan (jarak ke Pelabuhan "
                "Internasional General Santos dan Davao hanya ±140 mil laut).\n\n"
                "Fitur Kunci & Keunggulan Komparatif:\n"
                "• Koridor Pelayaran Internasional ALKI II: Berada tepat pada Alur Laut Kepulauan Indonesia (ALKI II) yang menghubungkan "
                "jalur logistik benua Australia, Selat Lombok, dan Selat Makassar menuju pusat-pusat perdagangan Asia Timur.\n"
                "• Wilayah Pengelolaan Perikanan (WPPNRI 716): Lumbung sumber daya perikanan laut dalam bernilai ekonomi tinggi, "
                "termasuk tuna sirip kuning (yellowfin tuna), cakalang, dan budidaya rumput laut cottonii pasifik.\n"
                "• Pusat Kawasan Strategis Nasional (PKSN) Tahuna: Berfungsi sebagai simpul pintu gerbang ekonomi internasional "
                "perbatasan serta pusat pelayanan terpadu investasi Indonesia Timur."
            )
        },
        {
            "code": "Gambar A.2",
            "title": "Diagram Alir Tahapan Penyelenggaraan Inovasi SIPOTENSI SANGIHE",
            "image": "public/images/proposal/gambar_a2.png",
            "url": "http://127.0.0.1:8000/images/proposal/gambar_a2.png",
            "category": "Bagian A: Waktu Penyelenggaraan Inovasi",
            "desc": (
                "Bagan ini merangkum peta jalan (roadmap) metodologi pengembangan sistem SIPOTENSI SANGIHE yang dilaksanakan "
                "secara bertahap dan terukur dari awal perumusan gagasan hingga implementasi operasional penuh (Tahun 2026):\n\n"
                "• Tahap I (Maret – April 2024): Riset kebutuhan promosi penanaman modal daerah kepulauan, telaah regulasi OSS-RBA, "
                "identifikasi 29 proyek unggulan di 15 kecamatan, serta survei validasi titik GPS primer di lapangan.\n"
                "• Tahap II (Mei – Juli 2024): Standardisasi batas administratif spasial GeoJSON 167 desa/kelurahan, integrasi basis data "
                "relasional PostgreSQL/PostGIS, dan penyelarasan zonasi terhadap RTRW Kabupaten Kepulauan Sangihe.\n"
                "• Tahap III (Agustus – Desember 2024): Pembangunan arsitektur backend Laravel 11, peta Leaflet JS, integrasi telemetri "
                "cuaca maritim BMKG Stasiun Naha, dan modul generator prospektus investasi resmi format PDF.\n"
                "• Tahap IV (Januari – Juni 2025): Peluncuran portal akun investor mandiri, digitalisasi formulir Letter of Intent (e-LoI) "
                "standar BKPM RI, serta back-office verifikasi permohonan admin Filament 3.\n"
                "• Tahap V (Tahun 2026 - Penuh): Implementasi fitur unggulan diferensiasi mencakup Model 3D Digital Twin WebGL Three.js, "
                "Kalkulator Finansial Simulasi ROI/BEP interaktif, Matriks Komparasi Head-to-Head, dan Dashboard Statistik Terbuka."
            )
        },
        {
            "code": "Gambar A.3",
            "title": "Kerangka Logis Indikator Keberhasilan (Output, Outcome, Benefit)",
            "image": "public/images/proposal/gambar_a3.png",
            "url": "http://127.0.0.1:8000/images/proposal/gambar_a3.png",
            "category": "Bagian A: Indikator Keberhasilan Inovasi",
            "desc": (
                "Kerangka logis (Logical Framework Analysis) ini membuktikan hubungan sebab-akibat (causality) yang terukur dan "
                "akuntabel dari eksistensi inovasi SIPOTENSI SANGIHE:\n\n"
                "1. Tingkat Keluaran Nyata (Output):\n"
                "   - 1 Unit Aplikasi WebGIS Terpadu mencakup 15 kecamatan dan 167 desa/kelurahan.\n"
                "   - 29 Titik Proyek Investasi Unggulan terverifikasi bernilai total Rp 131,0 Miliar.\n"
                "   - 1 Modul Model 3D Digital Twin WebGL, 1 Kalkulator Finansial ROI, dan 1 Matriks Komparasi.\n"
                "   - 1 Generator Prospektus PDF Resmi A4 dan 1 Portal Digital Letter of Intent (e-LoI).\n\n"
                "2. Tingkat Dampak Strategis (Outcome):\n"
                "   - Efisiensi waktu dan biaya survei awal (due diligence) calon investor meningkat drastis >85%.\n"
                "   - Terwujudnya Satu Data (Single Source of Truth) investasi daerah yang sinkron dengan Perda RTRW.\n"
                "   - Informasi potensi kepulauan dapat diakses 24 jam nonstop dari seluruh dunia menembus hambatan pulau terluar.\n\n"
                "3. Tingkat Kemanfaatan Pembangunan (Benefit):\n"
                "   - Percepatan realisasi modal PMDN dan PMA di Kabupaten Kepulauan Sangihe.\n"
                "   - Penyerapan tenaga kerja lokal di sektor perikanan tangkap tuna, budidaya rumput laut, dan pala rakyat.\n"
                "   - Penguatan Pendapatan Asli Daerah (PAD) untuk menopang kemandirian fiskal perbatasan."
            )
        },
        {
            "code": "Gambar B.1",
            "title": "Diagram Alir Proses Layanan Investasi Digital (Pencarian s.d. e-LoI)",
            "image": "public/images/proposal/gambar_b1.png",
            "url": "http://127.0.0.1:8000/images/proposal/gambar_b1.png",
            "category": "Bagian B: Analisis Kriteria Penilaian Inovasi",
            "desc": (
                "Bagan ini menyajikan Standard Operating Procedure (SOP) dan perjalanan pengguna digital (digital investor journey) "
                "dari tahap eksplorasi awal hingga fasilitasi perizinan terpadu:\n\n"
                "• Langkah 01 (Akses & Filter Spasial): Investor membuka portal /map, menerapkan filter komoditas sektor, "
                "kesiapan infrastruktur jalan/listrik/air/internet, dan rentang skala permodalan.\n"
                "• Langkah 02 (Eksplorasi Spasial 3D): Investor menelaah topografi perbukitan/pesisir melalui model 3D WebGL, "
                "memeriksa batas poligon kelurahan, dan memantau parameter cuaca maritim BMKG.\n"
                "• Langkah 03 (Simulasi Finansial ROI & BEP): Investor menguji skenario belanja modal (CAPEX), asumsi ROI, "
                "horizon waktu tenor modal, serta membandingkan kelayakan antarlokasi pada matriks komparasi.\n"
                "• Langkah 04 (Unduh Prospektus Resmi A4): Mengunduh dokumen eksekutif resmi berstempel dan kop pemda untuk "
                "bahan pengesahan pada forum rapat direksi pemodal.\n"
                "• Langkah 05 (Pengajuan Digital e-LoI): Mendaftarkan akun perusahaan dan mengirim formulir minat resmi standar BKPM RI.\n"
                "• Langkah 06 (Verifikasi & Fasilitasi DPMPTSP): Petugas DPMPTSP memverifikasi data di back-office CMS Filament, "
                "menjadwalkan audiensi konsultasi teknis, dan mengawal perizinan via OSS-RBA (SLA respons maksimal 1x24 jam)."
            )
        },
        {
            "code": "Gambar L.1",
            "title": "Antarmuka Peta Spasial Interaktif WebGIS Multi-Layer Kabupaten Sangihe",
            "image": "public/images/proposal/gambar_l1.png",
            "url": "http://127.0.0.1:8000/map",
            "category": "Lampiran Antarmuka Aplikasi Utama",
            "desc": (
                "Tangkapan layar antarmuka geospasial interaktif portal SIPOTENSI SANGIHE yang menyajikan visualisasi menyeluruh "
                "terhadap 29 titik proyek investasi siap tawar senilai total Rp 131,0 Miliar.\n\n"
                "Komponen Unggulan Peta:\n"
                "• Marker Berkode Warna Sektoral: Biru (Perikanan & Kelautan), Hijau (Pariwisata), Oranye (Pertanian & Perkebunan), "
                "Ungu (Energi Baru Terbarukan), dan Abu-abu (Industri & Infrastruktur).\n"
                "• Poligon Batas 167 Desa/Kelurahan: Visualisasi garis batas presisi berbasis GeoJSON PostGIS.\n"
                "• Sidebar Pintar Multi-Filter: Fasilitas filter multi-parameter berdasarkan ketersediaan akses jalan kontainer, "
                "jaringan listrik PLN, air bersih PDAM, dan konektivitas internet 4G.\n"
                "• Layer Switcher & Cuaca BMKG: Opsi penggantian peta dasar (Esri World Street Map, Citra Satelit HD, Dark Canvas) "
                "serta indikator peringatan gelombang dan angin BMKG Stasiun Maritim Sangihe."
            )
        },
        {
            "code": "Gambar L.2",
            "title": "Fitur Mode Perspektif & Visualisasi Model 3D Digital Twin Wilayah Investasi",
            "image": "public/images/proposal/gambar_l2.png",
            "url": "http://127.0.0.1:8000/map?view=3d",
            "category": "Lampiran Fitur Inovasi Unggulan (WebGL)",
            "desc": (
                "Tangkapan layar mode perspektif 3D Digital Twin bentang alam Kabupaten Kepulauan Sangihe yang dibangun menggunakan "
                "teknologi WebGL dan library Three.js tanpa memerlukan instalasi aplikasi tambahan pada komputer pengguna.\n\n"
                "Kemampuan Unggulan Fitur 3D:\n"
                "• Kontur Tiga Dimensi Interaktif: Peta dapat dimiringkan (pitch 48°) dan diputar (bearing rotation 360°) untuk "
                "menganalisis kelerengan lahan, elevasi perbukitan, serta kedalaman teluk alami.\n"
                "• Pin Markah 3D Melayang: Marker berdiri tegak lurus dengan pilar cahaya iluminasi (glow pillars) yang berdenyut "
                "secara dinamis untuk menegaskan lokasi proyek strategis.\n"
                "• HUD Controller Eksklusif: Panel kontrol kamera perspektif dan kartu proyek melayang (floating HUD card) yang "
                "memberikan pengalaman penjelajahan spasial modern sekelas software GIS mutakhir."
            )
        },
        {
            "code": "Gambar L.3",
            "title": "Fitur Kalkulator Simulasi Finansial & Proyeksi Arus Kas ROI Interaktif",
            "image": "public/images/proposal/gambar_l3.png",
            "url": "http://127.0.0.1:8000/potensi/1#kalkulator-investasi",
            "category": "Lampiran Modul Kelayakan Finansial",
            "desc": (
                "Tangkapan layar kalkulator kelayakan investasi interaktif yang tertanam pada setiap profil proyek potensi investasi, "
                "memfasilitasi calon penanam modal memodelkan skenario bisnis secara mandiri:\n\n"
                "Fitur & Output Kalkulasi Finansial:\n"
                "• Slider Modal Investasi (CAPEX): Fleksibilitas memilih alokasi permodalan dari ratusan juta hingga puluhan miliar rupiah.\n"
                "• Slider Asumsi ROI Tahunan: Menguji persentase imbal hasil (5% s.d. 40%/tahun) dengan kalkulasi otomatis Payback Period (BEP).\n"
                "• Grafik Lintasan Arus Kas (Cash Flow Trajectory): Diagram grafik visual Chart.js yang memproyeksikan akumulasi "
                "laba bersih kumulatif terhadap modal awal selama masa horizon investasi.\n"
                "• Estimasi Penyerapan Tenaga Kerja: Kalkulasi otomatis potensi penciptaan lapangan kerja lokal dari modal yang ditanamkan."
            )
        },
        {
            "code": "Gambar L.4",
            "title": "Fitur Matriks Komparasi Peluang Investasi Head-to-Head Multi-Kriteria",
            "image": "public/images/proposal/gambar_l4.png",
            "url": "http://127.0.0.1:8000/potensi/bandingkan?ids=1,2,3",
            "category": "Lampiran Alat Analisis Komparatif",
            "desc": (
                "Tangkapan layar fitur analitis komparasi Head-to-Head yang memungkinkan investor membandingkan hingga 3 (tiga) proyek "
                "sekaligus dalam satu tabel matriks yang terstruktur rapi:\n\n"
                "Dimensi Evaluasi Head-to-Head:\n"
                "• Dimensi Finansial: Perbandingan modal estimasi, target ROI tahunan, dan periode balik modal.\n"
                "• Dimensi Legalitas & Lahan: Perbandingan luas lahan, status hak tanah (hak milik, HPL daerah, sewa adat), dan RTRW.\n"
                "• Dimensi Utilitas Dasar: Ketersediaan jalan truk kontainer, daya listrik PLN, air bersih PDAM, dan internet 4G.\n"
                "• Dimensi Logistik Kewilayahan: Jarak dan estimasi waktu tempuh ke Pelabuhan Nusantara Tahuna dan Bandara Naha.\n"
                "• Matriks SWOT: Perbandingan komparatif kekuatan (Strengths), kelemahan (Weaknesses), peluang (Opportunities), dan ancaman (Threats)."
            )
        },
        {
            "code": "Gambar L.5",
            "title": "Dashboard Statistik & Infografis Investasi Publik Terbuka",
            "image": "public/images/proposal/gambar_l5.png",
            "url": "http://127.0.0.1:8000/statistik",
            "category": "Lampiran Satu Data & Transparansi Publik",
            "desc": (
                "Tangkapan layar portal statistik publik terbuka (/statistik) yang menyajikan transparansi data penanaman modal daerah "
                "untuk kepentingan masyarakat, jurnalis, akademisi, dan instansi perencana daerah:\n\n"
                "Komponen Infografis Terbuka:\n"
                "• 4 Kartu KPI Utama: Total Proyek Terbuka (29 Peluang), Total Estimasi Nilai Modal (Rp 131,0 Miliar), Cakupan Wilayah "
                "(15 Kecamatan), dan Rerata Estimasi ROI Daerah (18.4% / tahun).\n"
                "• Diagram Donat Komposisi Sektoral: Persentase kontribusi sektor Perikanan, Wisata Bahari, Pertanian/Pala, dan Energi.\n"
                "• Grafik Batang Distribusi Kecamatan: Sebaran proyek antarkecamatan guna memantau pemerataan pembangunan kepulauan.\n"
                "• Fitur Ekspor Data: Opsi pengunduhan data publik format CSV/Excel untuk mendukung riset ilmiah dan kajian perencana."
            )
        },
        {
            "code": "Gambar L.6",
            "title": "Dokumen Prospektus Investasi Format PDF Resmi Siap Cetak (A4)",
            "image": "public/images/proposal/gambar_l6.png",
            "url": "http://127.0.0.1:8000/potensi/1/pdf",
            "category": "Lampiran Dokumen Resmi Promosi",
            "desc": (
                "Tangkapan layar lembar prospektus penanaman modal berstandar resmi internasional ukuran A4 Portrait yang dihasilkan "
                "secara otomatis oleh sistem (generator PDF):\n\n"
                "Unsur Dokumen Resmi Prospektus:\n"
                "• Kop Surat Resmi Pemerintah Daerah: Memuat lambang resmi Kabupaten Kepulauan Sangihe yang simetris, instansi resmi, dan kontak DPMPTSP.\n"
                "• Profil Lengkap Proyek: Ulasan komprehensif latar belakang, koordinat GPS presisi, luas lahan, dan skema kemitraan bisnis.\n"
                "• Tabel Kelayakan Finansial & SWOT: Rincian belanja modal, proyeksi laba, analisis pasar, dan kepatuhan perizinan lingkungan.\n"
                "• QR Code Verifikasi: Dokumen siap cetak dan siap dilampirkan pada forum temu bisnis, pameran investasi internasional, "
                "maupun berkas delegasi penanaman modal luar negeri."
            )
        },
        {
            "code": "Gambar L.7",
            "title": "Portal Pendaftaran Akun Investor & Formulir Digital Letter of Intent (e-LoI)",
            "image": "public/images/proposal/gambar_l7.png",
            "url": "http://127.0.0.1:8000/investor/register",
            "category": "Lampiran Pelayanan Perizinan Terpadu",
            "desc": (
                "Tangkapan layar portal gerbang registrasi resmi bagi calon penanam modal dan fasilitas pengajuan minat investasi "
                "secara digital (e-LoI):\n\n"
                "Kelengkapan Formulir e-LoI Standar BKPM:\n"
                "• Registrasi Entitas Usaha: Form input terstandarisasi untuk Nama PIC/Direksi, Nomor Induk Berusaha (NIB), kontak WhatsApp, dan email bisnis.\n"
                "• Klasifikasi Penanaman Modal: Penegasan status modal (PMDN / PMA / Koperasi / Perorangan).\n"
                "• Komitmen Rencana Investasi: Input nominal alokasi modal, target penyerapan tenaga kerja lokal, dan model kerjasama yang diminati.\n"
                "• Fasilitasi Pemerintah: Pemilihan bentuk dukungan yang dimohonkan (fasilitasi perizinan OSS, sewa lahan pemda, atau mediasi kemitraan)."
            )
        }
    ]

    for item in figures_data:
        # Heading 1 for each Figure
        p_head = doc.add_paragraph()
        p_head.paragraph_format.space_before = Pt(12)
        p_head.paragraph_format.space_after = Pt(2)
        r_head = p_head.add_run(f"• {item['code']}: {item['title']}")
        r_head.font.name = "Arial"
        r_head.font.size = Pt(12.5)
        r_head.font.bold = True
        r_head.font.color.rgb = RGBColor(15, 41, 107)

        # Category Badge
        p_kat = doc.add_paragraph()
        p_kat.paragraph_format.space_after = Pt(8)
        r_kat = p_kat.add_run(f"Kategori: {item['category']} | URL Akses: {item['url']}")
        r_kat.font.size = Pt(8.5)
        r_kat.font.bold = True
        r_kat.font.color.rgb = RGBColor(37, 99, 235)

        # Image Embed
        img_path = os.path.abspath(item['image'])
        if os.path.exists(img_path):
            p_img = doc.add_paragraph()
            p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_img.paragraph_format.space_before = Pt(4)
            p_img.paragraph_format.space_after = Pt(4)
            p_img.add_run().add_picture(img_path, width=Inches(6.25))

            # Image Caption
            p_cap = doc.add_paragraph()
            p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_cap.paragraph_format.space_after = Pt(10)
            r_cap = p_cap.add_run(f"Tangkapan Layar {item['code']} — {item['title']}")
            r_cap.font.size = Pt(8.5)
            r_cap.font.italic = True
            r_cap.font.color.rgb = RGBColor(100, 116, 139)

        # Explanation Box
        t_desc = doc.add_table(rows=1, cols=1)
        t_desc.alignment = WD_TABLE_ALIGNMENT.CENTER
        t_desc.autofit = False
        c_desc = t_desc.rows[0].cells[0]
        c_desc.width = Inches(6.3)
        set_cell_background(c_desc, "F8FAFC")
        set_cell_margins(c_desc, top=100, bottom=100, left=140, right=140)
        
        p_desc_head = c_desc.paragraphs[0]
        r_desc_head = p_desc_head.add_run(f"PENJELASAN KOMPREHENSIF & NILAI INOVASI:")
        r_desc_head.bold = True
        r_desc_head.font.size = Pt(9.5)
        r_desc_head.font.color.rgb = RGBColor(15, 23, 42)

        for line in item['desc'].split('\n'):
            p_line = c_desc.add_paragraph()
            p_line.paragraph_format.line_spacing = 1.15
            p_line.paragraph_format.space_after = Pt(3)
            r_l = p_line.add_run(line)
            r_l.font.size = Pt(9.0)
            r_l.font.color.rgb = RGBColor(30, 41, 59)

        doc.add_page_break()

    # Save to multiple target locations
    output_path_root = os.path.abspath(r"c:\xampp\htdocs\gis\DOKUMENTASI_VISUAL_SIPOTENSI_SANGIHE.docx")
    output_path_pub = os.path.abspath(r"c:\xampp\htdocs\gis\public\DOKUMENTASI_VISUAL_SIPOTENSI_SANGIHE.docx")
    output_path_art = os.path.abspath(r"C:\Users\steve\.gemini\antigravity\brain\64b76a5e-9dea-4de1-be08-4a697f117eb4\DOKUMENTASI_VISUAL_SIPOTENSI_SANGIHE.docx")

    doc.save(output_path_root)
    doc.save(output_path_pub)
    doc.save(output_path_art)

    print(f"Document created successfully!")
    print(f"1. {output_path_root} ({os.path.getsize(output_path_root)} bytes)")
    print(f"2. {output_path_pub} ({os.path.getsize(output_path_pub)} bytes)")
    print(f"3. {output_path_art} ({os.path.getsize(output_path_art)} bytes)")

if __name__ == "__main__":
    create_document()
