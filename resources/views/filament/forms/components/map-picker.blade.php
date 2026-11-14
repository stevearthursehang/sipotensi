<div
    wire:ignore
    x-data="{
        map: null,
        marker: null,
        initMap() {
            if (this.map) return;

            const mapEl = this.$refs.mapContainer;
            if (!mapEl) return;

            // Load Leaflet assets if not present
            if (typeof L === 'undefined') {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);

                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = () => this.setupLeaflet();
                document.head.appendChild(script);
            } else {
                this.setupLeaflet();
            }
        },
        setupLeaflet() {
            const defaultLat = 3.58000000;
            const defaultLng = 125.58000000;

            let curLat = parseFloat($wire.get('data.latitude')) || defaultLat;
            let curLng = parseFloat($wire.get('data.longitude')) || defaultLng;

            this.map = L.map(this.$refs.mapContainer, {
                zoomControl: true,
                attributionControl: false
            }).setView([curLat, curLng], 12);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            }).addTo(this.map);

            const pinIcon = L.divIcon({
                className: 'filament-map-pin',
                html: `<div style='
                    background: #2563eb;
                    color: white;
                    width: 34px;
                    height: 34px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 3px solid white;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.35);
                    cursor: move;
                '><svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z'/><circle cx='12' cy='10' r='3'/></svg></div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -32]
            });

            this.marker = L.marker([curLat, curLng], {
                draggable: true,
                icon: pinIcon
            }).addTo(this.map);

            this.updateBadge(curLat, curLng);

            // Drag event
            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.syncCoords(pos.lat, pos.lng);
            });

            // Map click event
            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.syncCoords(e.latlng.lat, e.latlng.lng);
            });

            // Watch for changes from dropdown selections
            $wire.$watch('data.latitude', (val) => {
                if (val && !isNaN(val)) {
                    const newLat = parseFloat(val);
                    const newLng = parseFloat($wire.get('data.longitude')) || defaultLng;
                    this.marker.setLatLng([newLat, newLng]);
                    this.map.panTo([newLat, newLng]);
                    this.updateBadge(newLat, newLng);
                }
            });

            $wire.$watch('data.longitude', (val) => {
                if (val && !isNaN(val)) {
                    const newLat = parseFloat($wire.get('data.latitude')) || defaultLat;
                    const newLng = parseFloat(val);
                    this.marker.setLatLng([newLat, newLng]);
                    this.map.panTo([newLat, newLng]);
                    this.updateBadge(newLat, newLng);
                }
            });

            setTimeout(() => this.map.invalidateSize(), 300);
        },
        syncCoords(lat, lng) {
            const formattedLat = Number(lat).toFixed(8);
            const formattedLng = Number(lng).toFixed(8);
            $wire.set('data.latitude', formattedLat);
            $wire.set('data.longitude', formattedLng);
            this.updateBadge(formattedLat, formattedLng);
        },
        updateBadge(lat, lng) {
            const badge = this.$refs.coordBadge;
            if (badge) {
                badge.innerText = `Pin: ${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
            }
        },
        locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    if (this.marker && this.map) {
                        this.marker.setLatLng([lat, lng]);
                        this.map.setView([lat, lng], 15);
                        this.syncCoords(lat, lng);
                    }
                }, (err) => {
                    alert('Gagal mengambil lokasi GPS: ' + err.message);
                });
            }
        }
    }"
    x-init="initMap()"
    class="w-full"
    style="margin-top: 10px; margin-bottom: 12px;"
>
    <div style="
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    ">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 600; color: #334155;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/><path d="m14.83 14.83 4.24 4.24"/><path d="m9.17 14.83-4.24 4.24"/></svg>
                <span>Visual Pin Picker (Klik atau geser pin di peta untuk menentukan koordinat)</span>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <span x-ref="coordBadge" style="
                    background: #1e293b;
                    color: white;
                    font-size: 0.75rem;
                    font-family: monospace;
                    padding: 3px 8px;
                    border-radius: 6px;
                ">Pin: 3.580000, 125.580000</span>
                <button
                    type="button"
                    @click="locateUser()"
                    style="
                        background: white;
                        border: 1px solid #94a3b8;
                        border-radius: 6px;
                        padding: 3px 8px;
                        font-size: 0.75rem;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 4px;
                    "
                    title="Gunakan titik GPS perangkat saat ini"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3"/><path d="M12 19v3"/><path d="M2 12h3"/><path d="M19 12h3"/></svg>
                    <span>GPS Saya</span>
                </button>
            </div>
        </div>

        <div
            x-ref="mapContainer"
            style="
                height: 320px;
                width: 100%;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                z-index: 10;
            "
        ></div>
        <small style="display: block; margin-top: 6px; font-size: 0.73rem; color: #64748b;">
            * Titik pin akan otomatis menyesuaikan saat Anda memilih Kecamatan / Kelurahan di atas, dan sebaliknya nilai Latitude & Longitude di bawah akan otomatis terisi saat pin digeser di peta.
        </small>
    </div>
</div>

