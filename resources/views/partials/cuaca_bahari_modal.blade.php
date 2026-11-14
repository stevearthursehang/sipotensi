<!-- Modal Cuaca Bahari & Gelombang BMKG -->
<div class="modal fade" id="cuacaBahariModal" tabindex="-1" aria-labelledby="cuacaBahariModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-25 p-2 rounded-circle text-info">
                        <i class="bi bi-water fs-4"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" id="cuacaBahariModalLabel">
                            {{ __('messages.marine_weather_title') }}
                        </h6>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            {{ app()->getLocale() === 'en' ? 'Sangihe Islands Regency' : 'Kabupaten Kepulauan Sangihe' }} &bull; <span id="cuacaTimestamp">{{ __('messages.loading_time') }}</span>
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc;">
                <!-- Ringkasan Info Cuaca -->
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3 mb-4 rounded-3" style="background: #e0f2fe; color: #0369a1;">
                    <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
                    <div class="small">
                        <strong>{{ __('messages.marine_weather_desc') }}</strong>
                        <p class="mb-0 mt-1" id="cuacaStatusUmum">{{ __('messages.loading_status') }}</p>
                    </div>
                </div>

                <!-- Kartu Stasiun Perairan -->
                <div class="row g-3" id="cuacaZoneCards">
                    <div class="col-12 text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span>{{ __('messages.loading_status') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top d-flex justify-content-between">
                <small class="text-muted" style="font-size: 0.75rem;">
                    <i class="bi bi-broadcast me-1 text-primary"></i> {{ __('messages.bmkg_source') }}
                </small>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">{{ __('messages.modal_close') }}</button>
            </div>
        </div>
    </div>
</div>

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
                                                    <small class="text-muted d-block fs-8">{{ __('messages.wave_height') }}</small>
                                                    <span class="fw-bold text-primary fs-7">${z.tinggi_gelombang}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2 border">
                                                    <small class="text-muted d-block fs-8">{{ __('messages.wind_speed') }}</small>
                                                    <span class="fw-bold text-dark fs-7">${z.kecepatan_angin}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mb-2">
                                            <span><i class="bi bi-thermometer-half text-danger"></i> {{ __('messages.sea_temp') }}:</span>
                                            <strong class="text-dark">${z.suhu_laut}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mb-2">
                                            <span><i class="bi bi-compass text-info"></i> {{ __('messages.wind_direction') }}:</span>
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

