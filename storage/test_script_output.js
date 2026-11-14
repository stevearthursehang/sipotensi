<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('cuacaBahariModal');
        let cuacaLoaded = false;

        modal.addEventListener('show.bs.modal', function () {
            if (cuacaLoaded) return;
            fetch('/api/cuaca-bahari')
                .then(res => res.json())
                .then(data => {
                    cuacaLoaded = true;
                    document.getElementById('cuacaTimestamp').innerText = data.waktu_pengamatan || '';
                    document.getElementById('cuacaStatusUmum').innerText = data.status_umum || '';

                    const container = document.getElementById('cuacaZoneCards');
                    container.innerHTML = '';

                    (data.stasiun || []).forEach(z => {
                        let badgeBg = 'bg-success text-white';
                        let iconStatus = 'bi-check-circle-fill';
                        if (z.status_code === 'warning') {
                            badgeBg = 'bg-warning text-dark';
                            iconStatus = 'bi-exclamation-triangle-fill';
                        } else if (z.status_code === 'danger') {
                            badgeBg = 'bg-danger text-white';
                            iconStatus = 'bi-x-octagon-fill';
                        }

                        const cardHtml = `
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-dark fs-7">${z.nama}</h6>
                                        <span class="badge ${badgeBg} rounded-pill font-monospace fs-8">
                                            <i class="bi ${iconStatus} me-1"></i>${z.kategori_gelombang}
                                        </span>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i>${z.lokasi}</p>
                                        <div class="row g-2 text-center mb-3">
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2 border">
                                                    <small class="text-muted d-block fs-8">Wave Height</small>
                                                    <span class="fw-bold text-primary fs-7">${z.tinggi_gelombang}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2 border">
                                                    <small class="text-muted d-block fs-8">Wind Speed</small>
                                                    <span class="fw-bold text-dark fs-7">${z.kecepatan_angin}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mb-2">
                                            <span><i class="bi bi-thermometer-half text-danger"></i> Sea Surface Temperature:</span>
                                            <strong class="text-dark">${z.suhu_laut}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mb-2">
                                            <span><i class="bi bi-compass text-info"></i> Wind Direction:</span>
                                            <strong class="text-dark">${z.arah_angin}</strong>
                                        </div>
                                        <div class="alert alert-light border small mb-0 p-2 text-dark rounded-2">
                                            <strong>Status:</strong> ${z.status_keselamatan}
                                            <div class="text-muted mt-1 fs-8">${z.rekomendasi}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += cardHtml;
                    });
                })
                .catch(err => {
                    console.error('Failed to load marine weather:', err);
                });
        });
    });
</script>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS (CDN first with local offline fallback) -->
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" onerror="this.onerror=null;var s=document.createElement('script');s.src='/vendor/leaflet/leaflet.js';document.head.appendChild(s);"></script>
    <!-- Three.js & 3D libraries loaded with CDN + local fallback & deferred so they never block Leaflet map initialization or cause devtunnel timeouts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js" onerror="this.onerror=null;var s=document.createElement('script');s.src='/js/three.min.js';document.head.appendChild(s);" defer></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js" onerror="this.onerror=null;var s=document.createElement('script');s.src='/js/OrbitControls.js';document.head.appendChild(s);" defer></script>
<script src="/js/digital-twin-3d.js?v=1793411330" defer></script>
<script>
    const i18n = {
        locale: 'en',
        isEn: true,
        distPrefix: 'Dist. ',
        currencyBillion: 'Billion',
        currencyMillion: 'Million',
        noPotensiTitle: 'No investment opportunities found',
        noPotensiDesc: 'Try changing your search keywords or resetting filters.',
        loadError: 'Failed to load investment data.',
        retry: 'Try Again',
        adminArea: 'Administrative Territory',
        areaSize: 'Total Area',
        population: 'Population',
        inhabitants: 'Inhabitants',
        availInfra: 'Available Infrastructure:',
        road: 'Road',
        power: 'Electricity',
        water: 'Water',
        topPotentials: 'Top Potentials',
        estimatedInvestment: 'Estimated Investment Value',
        estimatedRoiLabel: 'Estimated Annual ROI:',
        perYear: '/ Year',
        landPermitReadiness: 'Land &amp; Permit Readiness:',
        viewProspectus: 'View Full Prospectus',
        visualize3d: '3D Model Visualization',
        compareOpportunity: 'Compare Opportunity',
        expressInterest: 'Express Investment Interest',
        btnDetail: 'Detail',
        btnCompare: 'Compare with other opportunities',
        btnFocus: 'Focus on map',
        btn3d: '3D Model Visualization',
        compareMaxAlert: 'Maximum 3 investment opportunities can be compared simultaneously.',
        measuredDistance: 'Measured Distance',
        meters: 'meters',
        waveHeight: 'Wave Height',
        windSpeed: 'Wind Speed',
        seaTemp: 'Sea Surface Temperature',
        viewBmkgBulletin: 'Open Full BMKG Bulletin',
        scaleSmall: 'Small',
        scaleMedium: 'Medium',
        scaleLarge: 'Large',
        landAvailable: 'Available',
        landInProcess: 'In Process',
        landUnavailable: 'Unavailable',
        sectors: {
            'pertanian': 'Agriculture &amp; Plantation',
            'perikanan': 'Fisheries &amp; Marine',
            'pariwisata': 'Tourism &amp; Culture',
            'peternakan': 'Livestock',
            'industri': 'Industry &amp; Processing',
            'energi': 'Renewable Energy',
            'perdagangan': 'Trade &amp; Logistics'
        }
    };
    window.i18n = i18n;

    const initialPotensiData = [{"id":6,"kelurahan_id":169,"nama":"Budidaya Rumput Laut Eucheuma Cottonii Kendahe","jenis_potensi":"Perikanan \u0026 Kelautan","komoditas_unggulan":"Rumput Laut Kering, Alkali Treated Carrageenan (ATC)","skala_peluang":"menengah","nilai_estimasi_investasi":"2800000000.00","roi_estimasi":"26.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Perairan teluk Kendahe berarus tenang dan jernih, ideal untuk hamparan budidaya rumput laut skala industri dengan pasokan bahan baku kontinu bagi industri farmasi dan pangan.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:09.000000Z","updated_at":"2026-10-23T23:29:09.000000Z","alamat":"Kampung Ulung Peliang, Kec. Tamako, Kab. Kepulauan Sangihe","latitude":"3.50074995","longitude":"125.54501216","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":169,"nama":"Ulung Peliang","kecamatan":"Tamako","latitude":"3.50074995","longitude":"125.54501216","luas_wilayah":1008.83,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":5,"kelurahan_id":79,"nama":"Ekowisata Air Terjun Kadadima \u0026 Hutan Lindung Gunung Sahendaruman","jenis_potensi":"Pariwisata","komoditas_unggulan":"Birdwatching (Burung Seriwang Sangihe), Glamping \u0026 Jungle Trekking","skala_peluang":"menengah","nilai_estimasi_investasi":"3500000000.00","roi_estimasi":"19.50","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Air terjun berundak tiga di kaki Gunung Sahendaruman yang asri, habitat satwa endemik langka seperti Burung Seriwang Sangihe. Peluang fasilitas jungle glamping ramah lingkungan.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:08.000000Z","updated_at":"2026-10-23T23:29:08.000000Z","alamat":"Kampung Lapepahe, Kec. Manganitu, Kab. Kepulauan Sangihe","latitude":"3.41441713","longitude":"125.58108165","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":79,"nama":"Lapepahe","kecamatan":"Manganitu Selatan","latitude":"3.41441713","longitude":"125.58108165","luas_wilayah":268.71,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":2,"kelurahan_id":4,"nama":"Gunung Api Bawah Laut Mahangetang (Banua Wuhu)","jenis_potensi":"Pariwisata","komoditas_unggulan":"Underwater Volcano Diving, Geotourism, Liveaboard Tour","skala_peluang":"besar","nilai_estimasi_investasi":"25000000000.00","roi_estimasi":"30.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Fenomena gunung api bawah laut aktif langka di dunia dengan gelembung air hangat dan terumbu karang spektakuler. Daya tarik luar biasa bagi turis mancanegara dan ekspedisi bawah laut.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:00.000000Z","updated_at":"2026-10-23T23:29:00.000000Z","alamat":"Kampung Aha Patung, Kec. Tabukan Selatan, Kab. Kepulauan Sangihe","latitude":"3.45686086","longitude":"125.65055013","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":4,"nama":"Aha Patung","kecamatan":"Tabukan Selatan Tengah","latitude":"3.45686086","longitude":"125.65055013","luas_wilayah":229.82,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":3,"kelurahan_id":10,"nama":"Kawasan Agrowisata \u0026 Pengolahan Pala Manganitu","jenis_potensi":"Pertanian \u0026 Perkebunan","komoditas_unggulan":"Pala Sangihe Super, Minyak Atsiri Pala, Manisan Pala","skala_peluang":"menengah","nilai_estimasi_investasi":"5000000000.00","roi_estimasi":"22.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Pala Sangihe dikenal memiliki kandungan minyak atsiri tertinggi. Potensi pengembangan pabrik penyulingan minyak pala modern terintegrasi dengan paket agrowisata pemetikan pala.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:02.000000Z","updated_at":"2026-10-23T23:29:02.000000Z","alamat":"Kampung Barangka, Kec. Manganitu, Kab. Kepulauan Sangihe","latitude":"3.57476234","longitude":"125.51088311","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":10,"nama":"Barangka","kecamatan":"Manganitu","latitude":"3.57476234","longitude":"125.51088311","luas_wilayah":133.34,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true}},{"id":30,"kelurahan_id":148,"nama":"Pelabuhan Tua Tahuna","jenis_potensi":"Pariwisata","komoditas_unggulan":"Keindahan bawah laut","skala_peluang":"menengah","nilai_estimasi_investasi":"200000000.00","roi_estimasi":"5.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Penelitian terbaru yang diterbitkan dalam jurnal Kapata Arkeologi oleh BRIN pada November 2025 mendokumentasikan sejumlah karakteristik bangkai kapal tersebut. \n\nKapata Arkeologi\n\n\n\n- Perkiraan masa tenggelam: Perang Dunia II, antara 1942\u20131945.\n- Lokasi: Teluk Tahuna, dekat kawasan Pelabuhan Tua.\n- Kedalaman: sekitar 18\u201325 meter.\n- Ukuran: panjang sekitar 40 meter, tinggi 5 meter, dan lebar 7 meter.\n- Jenis kapal: diduga kapal dagang Jepang yang dimodifikasi untuk kebutuhan logistik militer.\n- Bukti identifikasi: keberadaan ruang mesin, palka, baling-baling, serta aksara Jepang berupa kanji dan katakana pada lampu di ruang mesin. \n  \n  Kapata Arkeologi\n2. Apakah kapal itu ditenggelamkan kapal selam Amerika?\nAda dugaan yang menarik. Artikel penelitian arsitektur Museum Kapal di Kota Tahuna: Arsitektur Metafora yang diterbitkan pada 2023 menghubungkan kapal karam di Teluk Tahuna dengan serangan kapal selam Amerika Serikat, USS Paddle, di pantai barat Sangihe pada 6 Juli 1944. \n\nJurnal Arsitektur DASENG\n\n\n\n\nNamun, penelitian arkeologi BRIN tahun 2025 belum menetapkan secara pasti nama kapal, tanggal tenggelam, atau penyebab karamnya. Jadi, hubungan dengan serangan USS Paddle sebaiknya diperlakukan sebagai hipotesis sejarah yang masih perlu dibuktikan, bukan fakta final.","analisis_swot":null,"foto_utama":"potensi-investasi\/foto-utama\/01M5V5Z5P6FEBNNZJG4NG0ZQT0.jpg","galeri_foto":["potensi-investasi\/galeri\/01M5V5Z5PQB010MBXM0FG6R7A5.jpg"],"created_at":"2026-10-26T04:02:18.000000Z","updated_at":"2026-10-26T04:02:18.000000Z","alamat":"Kecamatan Tahuna, Kab. Kepulauan Sangihe","latitude":"3.60628198","longitude":"125.50034436","videos":null,"pdfs":["potensi-investasi\/pdf\/01M5V5Z5Q1FQ9HE489X41B7SQN.pdf"],"status_kesiapan_lahan":"siap","akses_jalan":true,"listrik":false,"air_bersih":false,"internet":true,"jarak_pelabuhan":"3.00","jarak_bandara":"44.00","kelurahan":{"id":148,"nama":"Soataloara I","kecamatan":"Tahuna","latitude":"3.61478558","longitude":"125.49108904","luas_wilayah":65.83,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":12,"kelurahan_id":7,"nama":"Peluang Energi Terbarukan Bahu","jenis_potensi":"Energi Terbarukan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bahu","skala_peluang":"kecil","nilai_estimasi_investasi":"3600000000.00","roi_estimasi":"25.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bahu.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:42.000000Z","updated_at":"2026-10-23T23:29:42.000000Z","alamat":"Jl. Poros Bahu, Kec. Tabukan Utara","latitude":"3.71005370","longitude":"125.48807437","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":7,"nama":"Bahu","kecamatan":"Tabukan Utara","latitude":"3.71005370","longitude":"125.48807437","luas_wilayah":477.89,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":20,"kelurahan_id":21,"nama":"Peluang Energi Terbarukan Beha","jenis_potensi":"Energi Terbarukan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Beha","skala_peluang":"besar","nilai_estimasi_investasi":"800000000.00","roi_estimasi":"15.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Beha.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:54.000000Z","updated_at":"2026-10-23T23:29:54.000000Z","alamat":"Jl. Poros Beha, Kec. Tabukan Utara","latitude":"3.67922846","longitude":"125.49921470","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":21,"nama":"Beha","kecamatan":"Tabukan Utara","latitude":"3.67922846","longitude":"125.49921470","luas_wilayah":669.05,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":22,"kelurahan_id":22,"nama":"Peluang Energi Terbarukan Belengang","jenis_potensi":"Energi Terbarukan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Belengang","skala_peluang":"menengah","nilai_estimasi_investasi":"800000000.00","roi_estimasi":"26.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Belengang.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:56.000000Z","updated_at":"2026-10-23T23:29:56.000000Z","alamat":"Jl. Poros Belengang, Kec. Manganitu","latitude":"3.48956101","longitude":"125.50313217","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":22,"nama":"Belengang","kecamatan":"Manganitu","latitude":"3.48956101","longitude":"125.50313217","luas_wilayah":508.73,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":16,"kelurahan_id":8,"nama":"Peluang Industri \u0026 Pengolahan Bakalaeng","jenis_potensi":"Industri \u0026 Pengolahan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bakalaeng","skala_peluang":"kecil","nilai_estimasi_investasi":"800000000.00","roi_estimasi":"21.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bakalaeng.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:49.000000Z","updated_at":"2026-10-23T23:29:49.000000Z","alamat":"Jl. Poros Bakalaeng, Kec. Manganitu","latitude":"3.58347607","longitude":"125.50491850","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":8,"nama":"Bakalaeng","kecamatan":"Manganitu","latitude":"3.58347607","longitude":"125.50491850","luas_wilayah":185.77,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":15,"kelurahan_id":18,"nama":"Peluang Industri \u0026 Pengolahan Bebu","jenis_potensi":"Industri \u0026 Pengolahan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bebu","skala_peluang":"kecil","nilai_estimasi_investasi":"4200000000.00","roi_estimasi":"26.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bebu.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:47.000000Z","updated_at":"2026-10-23T23:29:47.000000Z","alamat":"Jl. Poros Bebu, Kec. Tamako","latitude":"3.42057679","longitude":"125.52784289","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":18,"nama":"Bebu","kecamatan":"Tamako","latitude":"3.42057679","longitude":"125.52784289","luas_wilayah":220.03,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":18,"kelurahan_id":19,"nama":"Peluang Industri \u0026 Pengolahan Beeng Laut","jenis_potensi":"Industri \u0026 Pengolahan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Beeng Laut","skala_peluang":"besar","nilai_estimasi_investasi":"4600000000.00","roi_estimasi":"23.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Beeng Laut.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:52.000000Z","updated_at":"2026-10-23T23:29:52.000000Z","alamat":"Jl. Poros Beeng Laut, Kec. Tabukan Selatan","latitude":"3.48079018","longitude":"125.73192754","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":19,"nama":"Beeng Laut","kecamatan":"Tabukan Selatan Tengah","latitude":"3.48079018","longitude":"125.73192754","luas_wilayah":44.35,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true}},{"id":26,"kelurahan_id":34,"nama":"Peluang Industri \u0026 Pengolahan Bowongkulu","jenis_potensi":"Industri \u0026 Pengolahan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bowongkulu","skala_peluang":"menengah","nilai_estimasi_investasi":"2900000000.00","roi_estimasi":"25.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bowongkulu.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:30:01.000000Z","updated_at":"2026-10-23T23:30:01.000000Z","alamat":"Jl. Poros Bowongkulu, Kec. Tabukan Utara","latitude":"3.63792639","longitude":"125.55281366","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":34,"nama":"Bowongkulu","kecamatan":"Tabukan Utara","latitude":"3.63792639","longitude":"125.55281366","luas_wilayah":101.28,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":9,"kelurahan_id":6,"nama":"Peluang Pariwisata Apengsembeka","jenis_potensi":"Pariwisata","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Apengsembeka","skala_peluang":"kecil","nilai_estimasi_investasi":"1500000000.00","roi_estimasi":"20.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Apengsembeka.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:38.000000Z","updated_at":"2026-10-23T23:29:38.000000Z","alamat":"Jl. Poros Apengsembeka, Kec. Tahuna","latitude":"3.60994747","longitude":"125.48255224","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":6,"nama":"Apengsembeka","kecamatan":"Tahuna","latitude":"3.60994747","longitude":"125.48255224","luas_wilayah":56.52,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true}},{"id":13,"kelurahan_id":16,"nama":"Peluang Pariwisata Batuwingkung","jenis_potensi":"Pariwisata","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Batuwingkung","skala_peluang":"kecil","nilai_estimasi_investasi":"3700000000.00","roi_estimasi":"23.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Batuwingkung.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:44.000000Z","updated_at":"2026-10-23T23:29:44.000000Z","alamat":"Jl. Poros Batuwingkung, Kec. Tabukan Selatan","latitude":"3.53223984","longitude":"125.65820705","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":16,"nama":"Batuwingkung","kecamatan":"Tabukan Selatan","latitude":"3.53223984","longitude":"125.65820705","luas_wilayah":59.55,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true}},{"id":19,"kelurahan_id":20,"nama":"Peluang Pariwisata Beeng","jenis_potensi":"Pariwisata","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Beeng","skala_peluang":"kecil","nilai_estimasi_investasi":"4100000000.00","roi_estimasi":"27.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Beeng.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:53.000000Z","updated_at":"2026-10-23T23:29:53.000000Z","alamat":"Jl. Poros Beeng, Kec. Tabukan Selatan","latitude":"3.46462188","longitude":"125.70290708","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":20,"nama":"Beeng","kecamatan":"Tabukan Selatan Tengah","latitude":"3.46462188","longitude":"125.70290708","luas_wilayah":299.2,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true}},{"id":24,"kelurahan_id":33,"nama":"Peluang Pariwisata Bowongkulu I","jenis_potensi":"Pariwisata","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bowongkulu I","skala_peluang":"kecil","nilai_estimasi_investasi":"3000000000.00","roi_estimasi":"18.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bowongkulu I.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:58.000000Z","updated_at":"2026-10-23T23:29:58.000000Z","alamat":"Jl. Poros Bowongkulu I, Kec. Tabukan Utara","latitude":"3.62623130","longitude":"125.53766561","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":33,"nama":"Bowongkulu I","kecamatan":"Tabukan Utara","latitude":"3.62623130","longitude":"125.53766561","luas_wilayah":273.44,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":21,"kelurahan_id":11,"nama":"Peluang Perdagangan \u0026 Jasa Barangkalang","jenis_potensi":"Perdagangan \u0026 Jasa","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Barangkalang","skala_peluang":"menengah","nilai_estimasi_investasi":"2700000000.00","roi_estimasi":"23.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Barangkalang.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:55.000000Z","updated_at":"2026-10-23T23:29:55.000000Z","alamat":"Jl. Poros Barangkalang, Kec. Manganitu","latitude":"3.47956478","longitude":"125.49988527","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":11,"nama":"Barangkalang","kecamatan":"Manganitu","latitude":"3.47956478","longitude":"125.49988527","luas_wilayah":343.71,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":10,"kelurahan_id":14,"nama":"Peluang Perdagangan \u0026 Jasa Batulewehe","jenis_potensi":"Perdagangan \u0026 Jasa","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Batulewehe","skala_peluang":"menengah","nilai_estimasi_investasi":"4300000000.00","roi_estimasi":"24.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Batulewehe.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:40.000000Z","updated_at":"2026-10-23T23:29:40.000000Z","alamat":"Jl. Poros Batulewehe, Kec. Tahuna","latitude":"3.59663770","longitude":"125.50006460","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":14,"nama":"Batulewehe","kecamatan":"Tahuna Timur","latitude":"3.59663770","longitude":"125.50006460","luas_wilayah":94.02,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true}},{"id":28,"kelurahan_id":23,"nama":"Peluang Perdagangan \u0026 Jasa Bengka","jenis_potensi":"Perdagangan \u0026 Jasa","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bengka","skala_peluang":"kecil","nilai_estimasi_investasi":"2200000000.00","roi_estimasi":"22.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bengka.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:30:04.000000Z","updated_at":"2026-10-23T23:30:04.000000Z","alamat":"Jl. Poros Bengka, Kec. Manganitu","latitude":"3.59485832","longitude":"125.52523850","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":23,"nama":"Bengka","kecamatan":"Manganitu","latitude":"3.59485832","longitude":"125.52523850","luas_wilayah":398.54,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":true}},{"id":17,"kelurahan_id":9,"nama":"Peluang Perikanan \u0026 Kelautan Balane","jenis_potensi":"Perikanan \u0026 Kelautan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Balane","skala_peluang":"menengah","nilai_estimasi_investasi":"3700000000.00","roi_estimasi":"27.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Balane.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:50.000000Z","updated_at":"2026-10-23T23:29:50.000000Z","alamat":"Jl. Poros Balane, Kec. Tamako","latitude":"3.46306958","longitude":"125.51941265","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":9,"nama":"Balane","kecamatan":"Tamako","latitude":"3.46306958","longitude":"125.51941265","luas_wilayah":179.73,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":11,"kelurahan_id":15,"nama":"Peluang Perikanan \u0026 Kelautan Batunderang","jenis_potensi":"Perikanan \u0026 Kelautan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Batunderang","skala_peluang":"menengah","nilai_estimasi_investasi":"3300000000.00","roi_estimasi":"23.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Batunderang.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:41.000000Z","updated_at":"2026-10-23T23:29:41.000000Z","alamat":"Jl. Poros Batunderang, Kec. Manganitu","latitude":"3.35261721","longitude":"125.61217993","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":15,"nama":"Batunderang","kecamatan":"Manganitu Selatan","latitude":"3.35261721","longitude":"125.61217993","luas_wilayah":297.87,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":27,"kelurahan_id":36,"nama":"Peluang Perikanan \u0026 Kelautan Bukide","jenis_potensi":"Perikanan \u0026 Kelautan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bukide","skala_peluang":"besar","nilai_estimasi_investasi":"4800000000.00","roi_estimasi":"27.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bukide.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:30:03.000000Z","updated_at":"2026-10-23T23:30:03.000000Z","alamat":"Jl. Poros Bukide, Kec. Tabukan Selatan","latitude":"3.51114050","longitude":"125.58297461","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":36,"nama":"Bukide","kecamatan":"Tabukan Selatan","latitude":"3.51114050","longitude":"125.58297461","luas_wilayah":580.29,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":23,"kelurahan_id":12,"nama":"Peluang Peternakan Basauh","jenis_potensi":"Peternakan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Basauh","skala_peluang":"besar","nilai_estimasi_investasi":"1300000000.00","roi_estimasi":"14.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Basauh.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:57.000000Z","updated_at":"2026-10-23T23:29:57.000000Z","alamat":"Jl. Poros Basauh, Kec. Tabukan Selatan","latitude":"3.46189161","longitude":"125.60979203","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":12,"nama":"Basauh","kecamatan":"Tabukan Selatan Tenggara","latitude":"3.46189161","longitude":"125.60979203","luas_wilayah":611.67,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true}},{"id":25,"kelurahan_id":13,"nama":"Peluang Peternakan Batubulan","jenis_potensi":"Peternakan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Batubulan","skala_peluang":"besar","nilai_estimasi_investasi":"2800000000.00","roi_estimasi":"18.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Batubulan.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:59.000000Z","updated_at":"2026-10-23T23:29:59.000000Z","alamat":"Jl. Poros Batubulan, Kec. Tabukan Utara","latitude":"2.79788314","longitude":"125.40273866","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":13,"nama":"Batubulan","kecamatan":"Tahuna Barat","latitude":"2.79788314","longitude":"125.40273866","luas_wilayah":341.5,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":14,"kelurahan_id":17,"nama":"Peluang Peternakan Bebalang","jenis_potensi":"Peternakan","komoditas_unggulan":"Hasil Bumi \u0026 Kelautan Bebalang","skala_peluang":"besar","nilai_estimasi_investasi":"1200000000.00","roi_estimasi":"22.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Peluang investasi strategis berbasis potensi lokal di wilayah Bebalang.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:46.000000Z","updated_at":"2026-10-23T23:29:46.000000Z","alamat":"Jl. Poros Bebalang, Kec. Manganitu","latitude":"3.34116918","longitude":"125.57197550","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":17,"nama":"Bebalang","kecamatan":"Manganitu Selatan","latitude":"3.34116918","longitude":"125.57197550","luas_wilayah":68.06,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":4,"kelurahan_id":5,"nama":"Sentra Industri Pengolahan \u0026 Cold Storage Ikan Tuna Tahuna","jenis_potensi":"Perikanan \u0026 Kelautan","komoditas_unggulan":"Ikan Tuna Sirip Kuning (Yellowfin Tuna), Cakalang Beku","skala_peluang":"besar","nilai_estimasi_investasi":"18000000000.00","roi_estimasi":"28.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Terletak strategis menghadap Laut Sulawesi dan WPP-NRI 716 dengan tangkapan tuna melimpah. Peluang pembangunan fasilitas rantai dingin (cold storage kapasitas 500 ton) dan ekspor langsung.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:06.000000Z","updated_at":"2026-10-23T23:29:06.000000Z","alamat":"Kampung Angges, Kec. Tahuna, Kab. Kepulauan Sangihe","latitude":"3.65399991","longitude":"125.45989029","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":5,"nama":"Angges","kecamatan":"Tahuna Barat","latitude":"3.65399991","longitude":"125.45989029","luas_wilayah":852.7,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}},{"id":8,"kelurahan_id":26,"nama":"Sentra Peternakan Sapi Potong \u0026 Pakan Hijauan Tamako","jenis_potensi":"Peternakan","komoditas_unggulan":"Sapi Peranakan Ongole \/ Sapi Bali, Silase Jagung","skala_peluang":"menengah","nilai_estimasi_investasi":"4000000000.00","roi_estimasi":"21.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Pengembangan padang penggembalaan di bawah perkebunan kelapa (pasture under coconut) untuk pembibitan dan penggemukan sapi potong guna memenuhi kebutuhan daging Sangihe dan Talaud.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:15.000000Z","updated_at":"2026-10-23T23:29:15.000000Z","alamat":"Kampung Binala, Kec. Tamako, Kab. Kepulauan Sangihe","latitude":"3.47300182","longitude":"125.53165335","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":26,"nama":"Binala","kecamatan":"Tamako","latitude":"3.47300182","longitude":"125.53165335","luas_wilayah":325.7,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":7,"kelurahan_id":35,"nama":"Wisata Bahari Gugusan Kepulauan Nusa Tabukan \u0026 Bukide","jenis_potensi":"Pariwisata","komoditas_unggulan":"Snorkeling Sanctuary, Homestay Pesisir, Kayaking","skala_peluang":"kecil","nilai_estimasi_investasi":"1200000000.00","roi_estimasi":"18.00","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Gugusan pulau kecil berair jernih toska dengan keragaman biota laut yang memesona. Sangat prospektif untuk homestay nelayan berbasis komunitas dan wisata bahari petualangan.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:29:13.000000Z","updated_at":"2026-10-23T23:29:13.000000Z","alamat":"Kampung Bukide, Kec. Nusa Tabukan, Kab. Kepulauan Sangihe","latitude":"3.78405087","longitude":"125.58941968","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":35,"nama":"Bukide","kecamatan":"Tabukan Selatan","latitude":"3.78405087","longitude":"125.58941968","luas_wilayah":160.9,"akses_jalan":true,"listrik":true,"air_bersih":false,"internet":false}},{"id":1,"kelurahan_id":38,"nama":"Wisata Bahari Pantai Pananuareng","jenis_potensi":"Pariwisata","komoditas_unggulan":"Resort Tepi Pantai, Diving Center, Sunset Cruise","skala_peluang":"besar","nilai_estimasi_investasi":"15000000000.00","roi_estimasi":"24.50","status_lahan":"tersedia","status_perizinan":"sudah_izin","deskripsi":"Kawasan pantai pasir putih eksotis dengan garis pantai panjang dan terumbu karang terjaga. Sangat potensial untuk eco-resort tepi laut berstandar internasional dan water sports.","analisis_swot":null,"foto_utama":null,"galeri_foto":null,"created_at":"2026-10-23T23:28:57.000000Z","updated_at":"2026-10-23T23:28:57.000000Z","alamat":"Kampung Bungalawang, Kec. Tabukan Tengah, Kab. Kepulauan Sangihe","latitude":"3.57415983","longitude":"125.56522179","videos":null,"pdfs":null,"status_kesiapan_lahan":null,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":true,"jarak_pelabuhan":null,"jarak_bandara":null,"kelurahan":{"id":38,"nama":"Bungalawang","kecamatan":"Tahuna","latitude":"3.57415983","longitude":"125.56522179","luas_wilayah":118.32,"akses_jalan":true,"listrik":true,"air_bersih":true,"internet":false}}];
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
            if (leafletLoadAttempts === 5) {
                console.warn('Leaflet not detected, proactively injecting CDN fallback...');
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                s.onload = function () {
                    setTimeout(initMapPage, 30);
                };
                document.head.appendChild(s);
            }
            if (leafletLoadAttempts < 60) {
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

        // Base Maps
        osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        currentBaseLayer = osmLayer;

        satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP',
            maxZoom: 19
        });

        darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            subdomains: 'abcd',
            maxZoom: 19,
            attribution: '&copy; CartoDB Dark Matter'
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
        const totalKel = 167;
        const totalPot = 29;
        const totalNilaiM = 131;

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
            '<i class="bi bi-compass"></i> OpenStreetMap': osmLayer,
            '<i class="bi bi-moon-stars"></i> Dark Matter GIS': darkLayer,
            '<i class="bi bi-globe-americas"></i> Satellite HD': satelliteLayer
        }, {
            '<i class="bi bi-pin-map-fill text-primary"></i> Investment Opportunities': potensiLayer,
            '<i class="bi bi-bounding-box text-info"></i> Districts / Administrative': kelurahanLayer,
            '🌊 BMKG Marine Weather': cuacaBahariLayer
        }, { position: 'bottomright' }).addTo(map);

        // Load Data
        loadKelurahansGeoJson();
        if (Array.isArray(initialPotensiData) && initialPotensiData.length > 0) {
            renderPotensiData(initialPotensiData, false);
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMapPage);
    } else {
        initMapPage();
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

        // Auto-fit to bounds only when explicitly requested and map has valid calculated dimensions
        if (shouldFitBounds && bounds.length > 0 && map && map._loaded) {
            try {
                const mapSize = map.getSize();
                if (mapSize && mapSize.x > 50 && mapSize.y > 50) {
                    map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
                }
            } catch (e) {
                console.warn('fitBounds skipped:', e);
            }
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
                    const nameEl = document.getElementById('modalPotensiGuestName');
            if (nameEl) nameEl.innerText = potensiNama;
            const loginBtn = document.getElementById('modalGuestLoginBtn');
            if (loginBtn) loginBtn.href = '/investor/login?redirect=' + encodeURIComponent('/potensi/' + potensiId);
            const regBtn = document.getElementById('modalGuestRegBtn');
            if (regBtn) regBtn.href = '/investor/register?redirect=' + encodeURIComponent('/potensi/' + potensiId);
            const waBtn = document.getElementById('modalGuestWaBtn');
            if (waBtn) waBtn.href = 'https://wa.me/6281234567890?text=' + encodeURIComponent('Halo DPMPTSP Sangihe, saya ingin berkonsultasi mengenai peluang investasi ' + potensiNama);
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
                        <a href="http://localhost/investor/dashboard" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3">
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
        fetch('/api/cuaca-bahari')
            .then(res => res.json())
            .then(data => {
                cuacaBahariLayer.clearLayers();
                (data.stasiun || []).forEach(st => {
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
            })
            .catch(e => console.log('Cuaca layer info:', e));
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