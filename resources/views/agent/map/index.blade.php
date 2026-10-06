<x-agent.layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; border-radius: 8px; z-index: 1; }
        .custom-marker { text-align: center; }
        .marker-pulse {
            border-radius: 50%; height: 32px; width: 32px; position: absolute;
            animation: pulse 2s infinite;
        }
        .marker-core {
            border-radius: 50%; border: 2px solid white; position: absolute; top: 50%; left: 50%;
        }
        .marker-label-pro {
            position: absolute; color: white; padding: 2px 6px; font-size: 10px; font-weight: bold; border-radius: 4px; left: 50%; transform: translateX(-50%); white-space: nowrap;
        }
        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
    @endpush

    <!-- Header Premium Componente -->
    <div class="tickets-shell">
        <x-agent.page-header title="Rastreo de Agentes" icon="<i class='bi bi-geo-alt-fill'></i>">
            Seguimiento estratégico de agentes en terreno en tiempo real.
            
            <x-slot name="actions">
                <button id="refresh-map" class="btn text-white d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                    <i class="bi bi-arrow-clockwise"></i> <span>Sincronizar</span>
                </button>
            </x-slot>
        </x-agent.page-header>
    </div>

    <div class="row g-4">
        <div class="col-lg-3 order-2 order-lg-1">
            <div class="card text-white h-100" style="background-color: #000; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px;">
                <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center bg-transparent pt-4 px-4 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">
                    <h5 class="mb-0 fw-bold fs-6">Agentes Activos</h5>
                    <span class="badge rounded-pill text-dark" style="background: white; padding: 0.35rem 0.6rem;" id="active-agents-count">0</span>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div id="no-agents-alert" class="text-center m-5" style="color: rgba(255,255,255,0.4);">
                        <i class="bi bi-geo-alt d-block mb-3" style="font-size: 2rem; opacity: 0.5;"></i>
                        <span style="font-size: 0.85rem; font-weight: 500;">Sin actividad reportada</span>
                    </div>
                    <div id="agent-list" class="list-group list-group-flush">
                        <!-- Ajax content -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 order-1 order-lg-2">
            <div class="card border-0 h-100 position-relative" style="background-color: #000; border-radius: 14px; overflow: hidden;">
                <!-- Flotante sobre el mapa -->
                <div id="map-overlay-alert" class="position-absolute top-0 end-0 m-3 z-3 text-white px-3 py-2" style="background: #0f172a; border-radius: 8px; font-size: 0.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                    <i class="bi bi-info-circle me-1" style="color: #60a5fa;"></i> No hay técnicos en camino
                </div>
                
                <div class="card-body p-0" id="mapContainerOuter">
                    <div id="map" style="border-radius: 14px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map', {zoomControl: false}).setView([0, 0], 2);
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        var markers = {};
        var agentGroup = L.featureGroup().addTo(map);

        function fetchLocations() {
            fetch('{{ route("agent.map.locations") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        updateMarkers(data.locations);
                    }
                })
                .catch(e => console.error('Error fetching locations:', e));
        }

        function updateMarkers(locations) {
            document.getElementById('active-agents-count').textContent = locations.length;
            
            var alertEl = document.getElementById('no-agents-alert');
            var overlayAlert = document.getElementById('map-overlay-alert');
            var sidebarList = document.getElementById('agent-list');
            
            if (locations.length === 0) {
                alertEl.classList.remove('d-none');
                overlayAlert.classList.remove('d-none');
                sidebarList.innerHTML = '';
            } else {
                alertEl.classList.add('d-none');
                overlayAlert.classList.add('d-none');
                sidebarList.innerHTML = '';
            }
            
            var currentIds = locations.map(l => l.staff_id);
            Object.keys(markers).forEach(id => {
                if (!currentIds.includes(parseInt(id))) {
                    agentGroup.removeLayer(markers[id]);
                    delete markers[id];
                }
            });

            locations.forEach(loc => {
                var popupContent = `
                    <div class="p-2 text-dark" style="min-width: 150px;">
                        <h6 class="fw-bold mb-1">${loc.name}</h6>
                        <div class="small mb-1">Ticket: #${loc.ticket_number}</div>
                        <span class="badge bg-success mb-2">${loc.status}</span>
                        <a href="/agent/ticket/${loc.ticket_id}" class="btn btn-primary btn-sm w-100 mt-1">Ver detalles</a>
                    </div>
                `;

                if (markers[loc.staff_id]) {
                    markers[loc.staff_id].setLatLng([loc.lat, loc.lng]);
                    markers[loc.staff_id].getPopup().setContent(popupContent);
                } else {
                    var customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `
                            <div class="marker-pulse" style="background: rgba(239, 68, 68, 0.4);"></div>
                            <div class="marker-core" style="background: #ef4444; width: 22px; height: 22px; margin: -11px 0 0 -11px;"></div>
                            <div class="marker-label-pro" style="background: #ef4444; border: 1px solid white; top: -35px;">${loc.name.split(' ')[0]}</div>
                        `,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    var marker = L.marker([loc.lat, loc.lng], {icon: customIcon}).bindPopup(popupContent);
                    markers[loc.staff_id] = marker;
                    agentGroup.addLayer(marker);
                }

                var item = document.createElement('a');
                item.href = "#";
                item.className = 'list-group-item list-group-item-action bg-transparent text-white border-secondary d-flex align-items-center gap-3 py-3';
                item.onclick = (e) => {
                    e.preventDefault();
                    map.flyTo([loc.lat, loc.lng], 17, { duration: 1.5 });
                    markers[loc.staff_id].openPopup();
                };
                
                var initial = loc.name.charAt(0).toUpperCase();
                item.innerHTML = `
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">${initial}</div>
                    <div class="min-width-0">
                        <div class="fw-bold text-truncate">${loc.name}</div>
                        <div class="small text-muted text-truncate">Ticket #${loc.ticket_number} • ${loc.status}</div>
                    </div>
                `;
                sidebarList.appendChild(item);
            });

            if (locations.length > 0 && agentGroup.getBounds().isValid()) {
                if (map.getZoom() < 3) {
                    map.fitBounds(agentGroup.getBounds(), {padding: [50, 50]});
                }
            }
        }

        fetchLocations();
        setInterval(fetchLocations, 15000);

        document.getElementById('refresh-map').addEventListener('click', function() {
            var btn = this;
            var icon = btn.querySelector('i');
            btn.disabled = true;
            icon.classList.add('spin');
            
            fetchLocations();
            
            setTimeout(() => {
                btn.disabled = false;
                icon.classList.remove('spin');
            }, 1000);
        });

        setTimeout(() => map.invalidateSize(), 200);
    });
    </script>
    @endpush
</x-agent.layout>