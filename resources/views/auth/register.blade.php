<x-auth.layout title="Crear cuenta">
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .login-panel { 
            padding: 40px !important; 
            background: transparent !important; 
            box-shadow: none !important;
            border: none !important;
        }
        .login-form { gap: 24px !important; }
        .form-grid { display: grid; gap: 24px !important; grid-template-columns: 1fr 1fr !important; align-items: start !important; }
        
        .map-btn { 
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
            background: #1e293b; color: #f8fafc; border: 1px solid #334155; 
            border-radius: 8px; padding: 13px 14px; font-size: 15px; font-weight: 600; 
            cursor: pointer; transition: all 0.2s; margin-top: 0; text-decoration: none; 
            width: 100%; box-sizing: border-box; 
        }
        .map-btn:hover { background: #334155; border-color: #475569; }

        @media (max-width: 760px) {
            .login-panel { padding: 22px !important; }
            .form-grid { grid-template-columns: 1fr !important; }
        }
        
        /* Map modal overlay styles */
        #mapModalOverlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
        #mapModalContent {
            background: #0f172a; width: 95%; max-width: 800px; border-radius: 12px; overflow: hidden;
            display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            border: 1px solid #1e293b;
        }
        
        #mapModalOverlay h3 { color: #f8fafc !important; }
        #mapModalHeader { border-bottom: 1px solid #1e293b !important; }
        #mapModalFooter { border-top: 1px solid #1e293b !important; background: #0f172a !important; }
        #mapSearchInput { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
        #btnCloseMap { background: #334155 !important; color: #e2e8f0 !important; }
        #btnLocateMe { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
        #btnCancelMap { background: #1e293b !important; color: #fff !important; border: 1px solid #334155 !important; }
    </style>
    @endpush

    <!-- PANEL DE REGISTRO -->
    <div class="login-panel" style="grid-template-columns: 1fr; width: 100%; max-width: 1000px; margin-left: auto; margin-right: auto; padding-top: 0 !important;">
        <div class="login-panel-left" style="width: 100%;">
            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                @if (session('status'))
                    <x-auth.alert type="success" :message="session('status')" />
                @endif
                @if ($errors->any())
                    <x-auth.alert type="danger" :message="$errors->first()" />
                @endif

                <div class="form-grid">
                    <x-auth.input 
                        id="firstname" 
                        type="text" 
                        name="firstname" 
                        label="Nombre" 
                        placeholder="Tu nombre" 
                        value="{{ old('firstname') }}"
                        required 
                    />

                    <x-auth.input 
                        id="lastname" 
                        type="text" 
                        name="lastname" 
                        label="Apellido" 
                        placeholder="Tu apellido" 
                        value="{{ old('lastname') }}"
                        required 
                    />

                    <x-auth.input 
                        id="email" 
                        type="email" 
                        name="email" 
                        label="Correo electrónico" 
                        placeholder="tu@email.com" 
                        value="{{ old('email') }}"
                        required 
                    />

                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label style="font-size: 14px; font-weight: 600; color: #f8fafc; margin-bottom: 8px;">Dirección</label>
                        <input type="hidden" name="address" id="address" value="{{ old('address') }}">
                        <input type="hidden" name="latitude" id="latInput" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="lngInput" value="{{ old('longitude') }}">
                        
                        <button type="button" class="map-btn" id="btnOpenMap" style="{{ (old('latitude') && old('longitude')) ? 'color: #166534; background: #f0fdf4; border-color: #bbf7d0;' : '' }}">
                            @if(old('latitude') && old('longitude'))
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 4px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Ubicación lista para registrarse.
                            @else
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M8 0C5.2 0 3 2.2 3 5c0 3.5 5 11 5 11s5-7.5 5-11c0-2.8-2.2-5-5-5zm0 7.5c-1.4 0-2.5-1.1-2.5-2.5S6.6 2.5 8 2.5 10.5 3.6 10.5 5 9.4 7.5 8 7.5z"/></svg>
                                Fijar ubicación exacta en el mapa
                            @endif
                        </button>
                    </div>

                    <x-auth.input 
                        id="phone" 
                        type="tel" 
                        name="phone" 
                        label="Teléfono" 
                        placeholder="Solo números" 
                        value="{{ old('phone') }}"
                        help="Entre 7 y 15 dígitos numéricos."
                        required 
                        inputmode="numeric"
                        pattern="\d{7,15}"
                        minlength="7"
                        maxlength="15"
                    />

                    <x-auth.input 
                        id="password" 
                        type="password" 
                        name="password" 
                        label="Contraseña" 
                        placeholder="Contraseña" 
                        help="Mínimo 6 caracteres"
                        required 
                    />
                    
                    <x-auth.input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        label="Confirmar Contraseña" 
                        placeholder="Confirmar contraseña" 
                        required 
                    />
                </div>

                <div style="margin-top: 32px; width: 100%;">
                    <x-auth.button style="width: 100%;">Crear cuenta</x-auth.button>
                </div>
            </form>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModalOverlay">
        <div id="mapModalContent">
            <div id="mapModalHeader" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #0f172a;">Selecciona tu ubicación</h3>
                <button type="button" id="btnCloseMap" style="background: none; border: none; font-size: 24px; line-height: 1; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            
            <div style="padding: 16px 20px; display: flex; gap: 10px;">
                <input type="text" id="mapSearchInput" placeholder="Buscar calle, barrio, lugar..." style="flex: 1; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                <button type="button" id="mapSearchBtn" style="padding: 10px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Buscar</button>
                <button type="button" id="btnLocateMe" style="padding: 10px 16px; background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 500; cursor: pointer;" title="Usar mi GPS">📍 GPS</button>
            </div>

            <div id="mapContainer" style="width: 100%; height: 400px; background: #f1f5f9;"></div>

            <div id="mapModalFooter" style="padding: 16px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 12px; background: #f8fafc;">
                <button type="button" id="btnCancelMap" style="padding: 10px 16px; background: #fff; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 500; color: #475569; cursor: pointer;">Cancelar</button>
                <button type="button" id="btnConfirmMap" style="padding: 10px 24px; background: #16a34a; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Confirmar Ubicación</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map, marker;
            var currentLat = 8.9824; // Panama default
            var currentLng = -79.5199;
            var mapContainer = document.getElementById('mapContainer');
            
            var btnOpenMap = document.getElementById('btnOpenMap');
            var btnCloseMap = document.getElementById('btnCloseMap');
            var btnCancelMap = document.getElementById('btnCancelMap');
            var btnConfirmMap = document.getElementById('btnConfirmMap');
            var modalOverlay = document.getElementById('mapModalOverlay');
            
            var latInput = document.getElementById('latInput');
            var lngInput = document.getElementById('lngInput');
            
            function initMap() {
                map = L.map(mapContainer).setView([currentLat, currentLng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                marker = L.marker([currentLat, currentLng], {draggable: true}).addTo(map);
                
                marker.on('dragend', function(e) {
                    var pos = marker.getLatLng();
                    currentLat = pos.lat;
                    currentLng = pos.lng;
                });
                
                map.on('click', function(e) {
                    currentLat = e.latlng.lat;
                    currentLng = e.latlng.lng;
                    marker.setLatLng(e.latlng);
                });
            }
            
            function openMap() {
                modalOverlay.style.display = 'flex';
                if (!map) {
                    initMap();
                }
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
            }
            
            btnOpenMap.addEventListener('click', openMap);
            btnCloseMap.addEventListener('click', () => modalOverlay.style.display = 'none');
            btnCancelMap.addEventListener('click', () => modalOverlay.style.display = 'none');
            
            btnConfirmMap.addEventListener('click', function() {
                latInput.value = currentLat.toFixed(6);
                lngInput.value = currentLng.toFixed(6);
                modalOverlay.style.display = 'none';
                
                btnOpenMap.style.color = '#166534';
                btnOpenMap.style.background = '#f0fdf4';
                btnOpenMap.style.borderColor = '#bbf7d0';
                btnOpenMap.innerHTML = '<i class="bi bi-check-circle-fill"></i> Ubicación confirmada';
            });
            
            // Search functionality
            document.getElementById('mapSearchBtn').addEventListener('click', function() {
                var query = document.getElementById('mapSearchInput').value;
                if(!query) return;
                
                fetch('https://photon.komoot.io/api/?q=' + encodeURIComponent(query + ', Panamá') + '&limit=1')
                    .then(res => res.json())
                    .then(data => {
                        if(data.features && data.features.length > 0) {
                            var coords = data.features[0].geometry.coordinates;
                            currentLat = coords[1];
                            currentLng = coords[0];
                            map.setView([currentLat, currentLng], 15);
                            marker.setLatLng([currentLat, currentLng]);
                        } else {
                            alert("No se encontraron resultados");
                        }
                    });
            });

            document.getElementById('btnLocateMe').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        currentLat = pos.coords.latitude;
                        currentLng = pos.coords.longitude;
                        map.setView([currentLat, currentLng], 15);
                        marker.setLatLng([currentLat, currentLng]);
                    });
                }
            });
        });
    </script>
    @endpush
</x-auth.layout>
