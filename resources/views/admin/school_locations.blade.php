<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Kelola Lokasi Sekolah') }}
            </h2>
            <button onclick="UIkit.modal('#modal-add-location').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-100 transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Lokasi</span>
            </button>
        </div>
    </x-slot>

    <!-- Leaflet.js Assets Inside the layout body -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        #map-add, #map-edit, #map-preview {
            height: 320px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            z-index: 10;
        }
        #map-preview {
            height: 400px;
        }
    </style>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-705 px-4 py-3 rounded-xl text-xs font-semibold space-y-1 shadow-sm">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Map Preview Card (Above Table) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo mb-6">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                        <span uk-icon="icon: location; ratio: 0.95"></span>
                        <span>Peta Pemantau Lokasi Sekolah (Preview)</span>
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">Pemantauan visual seluruh titik absensi aktif beserta radius jangkauannya</p>
                </div>
                <div class="p-4">
                    <div id="map-preview" class="w-full rounded-xl border border-slate-200 shadow-inner"></div>
                    <div class="mt-4 bg-slate-50 border border-slate-100 rounded-xl p-3 text-[10px] text-slate-550 font-semibold leading-relaxed flex items-start space-x-2">
                        <span uk-icon="icon: info; ratio: 0.75" class="mt-0.5 text-indigo-500"></span>
                        <span>Petunjuk: Klik pada baris di tabel bawah atau tombol preview (<span uk-icon="icon: shrink; ratio: 0.7"></span>) untuk memfokuskan peta ke lokasi tertentu.</span>
                    </div>
                </div>
            </div>

            <!-- Location Table Card (Below Map) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800">Daftar Titik Koordinat Absensi</h3>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">Titik lokasi referensi GPS untuk membatasi wilayah presensi siswa (Multi-Lokasi)</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Titik Lokasi</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lintang (Latitude)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bujur (Longitude)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Radius Jangkauan</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($locations as $loc)
                            <tr data-id="{{ $loc->id }}" data-lat="{{ $loc->latitude }}" data-lng="{{ $loc->longitude }}" class="location-row cursor-pointer hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">
                                    {{ $loc->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                    {{ $loc->latitude }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                    {{ $loc->longitude }}
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-semibold text-slate-700">
                                    {{ $loc->radius }} meter
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $loc->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $loc->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                                    <!-- Preview Button -->
                                    <button 
                                        data-id="{{ $loc->id }}"
                                        data-lat="{{ $loc->latitude }}"
                                        data-lng="{{ $loc->longitude }}"
                                        class="preview-location-btn text-xs font-bold text-emerald-600 hover:bg-emerald-50 px-2 py-1.5 rounded-xl transition"
                                        title="Preview di Peta"
                                    >
                                        <span uk-icon="icon: shrink; ratio: 0.85"></span>
                                    </button>

                                    <!-- Edit Button -->
                                    <button 
                                        data-id="{{ $loc->id }}"
                                        data-name="{{ $loc->name }}"
                                        data-lat="{{ $loc->latitude }}"
                                        data-lng="{{ $loc->longitude }}"
                                        data-radius="{{ $loc->radius }}"
                                        data-active="{{ $loc->is_active ? '1' : '0' }}"
                                        class="edit-location-btn text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-2 py-1.5 rounded-xl transition"
                                        title="Ubah"
                                    >
                                        <span uk-icon="icon: file-edit; ratio: 0.85"></span>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.school-locations.destroy', $loc->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus titik lokasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="event.stopPropagation();" class="text-xs font-bold text-rose-600 hover:bg-rose-50 px-2 py-1.5 rounded-xl transition" title="Hapus">
                                            <span uk-icon="icon: trash; ratio: 0.85"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada titik kordinat sekolah yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL ADD LOCATION -->
    <div id="modal-add-location" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="width: 1000px; max-width: 95%;">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus-circle; ratio: 1.1"></span>
                <span>Tambah Titik Lokasi Baru</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left: Form -->
                <form action="{{ route('admin.school-locations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Search Input (Paling Atas) -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-500 mb-1">Cari Alamat / Lokasi</label>
                        <input type="text" id="search-add" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ketik nama tempat/alamat untuk mencari..." autocomplete="off">
                        <!-- Dropdown Hasil Pencarian -->
                        <div id="results-add" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-[9999] hidden max-h-60 overflow-y-auto"></div>
                    </div>

                    <!-- Nama Lokasi / Gedung -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lokasi / Gedung</label>
                        <input type="text" name="name" id="add-name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Gedung Utama, Lab RPL">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Latitude (Lintang)</label>
                            <input type="number" step="any" name="latitude" id="add-lat-val" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: -6.2088">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Longitude (Bujur)</label>
                            <input type="number" step="any" name="longitude" id="add-lng-val" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 106.8456">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Radius Jangkauan (Meter)</label>
                        <input type="number" name="radius" id="add-radius-val" value="50" min="5" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked id="add-active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                            <label for="add-active" class="text-xs font-semibold text-slate-600">Aktifkan lokasi</label>
                        </div>
                        <button type="button" onclick="useCurrentLocation('add')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1.5 rounded-xl border border-slate-200 transition flex items-center space-x-1">
                            <span uk-icon="icon: location; ratio: 0.7"></span>
                            <span>Gunakan Lokasi Saya</span>
                        </button>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan</button>
                    </div>
                </form>

                <!-- Right: Map -->
                <div class="flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-500 mb-1 block">Tinjauan Peta</span>
                    <div id="map-add" class="w-full rounded-xl border border-slate-200 shadow-inner"></div>
                    <span class="text-[9px] text-slate-400 mt-1 block leading-relaxed">* Klik pada peta atau seret marker untuk memindahkan posisi koordinat secara akurat.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT LOCATION -->
    <div id="modal-edit-location" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="width: 1000px; max-width: 95%;">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Sunting Titik Lokasi</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left: Form -->
                <form id="edit-location-form" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <!-- Search Input (Paling Atas) -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-500 mb-1">Cari Alamat / Lokasi</label>
                        <input type="text" id="search-edit" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ketik nama tempat/alamat untuk mencari..." autocomplete="off">
                        <!-- Dropdown Hasil Pencarian -->
                        <div id="results-edit" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-[9999] hidden max-h-60 overflow-y-auto"></div>
                    </div>

                    <!-- Nama Lokasi / Gedung -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lokasi / Gedung</label>
                        <input type="text" name="name" id="edit-name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Latitude (Lintang)</label>
                            <input type="number" step="any" name="latitude" id="edit-lat" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Longitude (Bujur)</label>
                            <input type="number" step="any" name="longitude" id="edit-lng" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Radius Jangkauan (Meter)</label>
                        <input type="number" name="radius" id="edit-radius" min="5" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" id="edit-active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                            <label for="edit-active" class="text-xs font-semibold text-slate-600">Lokasi Aktif</label>
                        </div>
                        <button type="button" onclick="useCurrentLocation('edit')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1.5 rounded-xl border border-slate-200 transition flex items-center space-x-1">
                            <span uk-icon="icon: location; ratio: 0.7"></span>
                            <span>Gunakan Lokasi Saya</span>
                        </button>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan Perubahan</button>
                    </div>
                </form>

                <!-- Right: Map -->
                <div class="flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-500 mb-1 block">Tinjauan Peta</span>
                    <div id="map-edit" class="w-full rounded-xl border border-slate-200 shadow-inner"></div>
                    <span class="text-[9px] text-slate-400 mt-1 block leading-relaxed">* Klik pada peta atau seret marker untuk memindahkan posisi koordinat secara akurat.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Leaflet & Integrasi Autocomplete -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data lokasi dari backend Laravel
            const locations = @json($locations);

            // ==========================================
            // 1. PETA PREVIEW UTAMA (DI ATAS TABEL)
            // ==========================================
            const defaultLat = -7.291081;
            const defaultLng = 112.780445;
            
            const mapPreview = L.map('map-preview').setView([defaultLat, defaultLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mapPreview);

            const previewMarkers = {};
            const previewCircles = {};

            locations.forEach(function(loc) {
                const lat = parseFloat(loc.latitude);
                const lng = parseFloat(loc.longitude);
                const radius = parseFloat(loc.radius);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    // Marker
                    const marker = L.marker([lat, lng]).addTo(mapPreview);
                    marker.bindPopup(`
                        <div class="p-1.5">
                            <h4 class="font-bold text-xs text-slate-800 m-0 mb-1">${loc.name}</h4>
                            <p class="text-[10px] text-slate-500 m-0">Lat: ${lat.toFixed(6)}</p>
                            <p class="text-[10px] text-slate-500 m-0">Lng: ${lng.toFixed(6)}</p>
                            <p class="text-[10px] font-bold text-indigo-600 m-0 mt-1">Radius: ${radius}m</p>
                        </div>
                    `);

                    // Circle
                    const circle = L.circle([lat, lng], {
                        color: '#6366f1',
                        fillColor: '#6366f1',
                        fillOpacity: 0.12,
                        radius: radius
                    }).addTo(mapPreview);

                    previewMarkers[loc.id] = marker;
                    previewCircles[loc.id] = circle;
                }
            });

            // Sempurnakan cakupan kamera jika ada lokasi terdaftar
            if (locations.length > 0) {
                const group = new L.featureGroup(Object.values(previewMarkers));
                mapPreview.fitBounds(group.getBounds().pad(0.12));
            }

            // Panning ketika baris data diklik
            document.querySelectorAll('.location-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Hindari trigger jika mengklik tombol aksi
                    if (e.target.closest('button') || e.target.closest('form')) {
                        return;
                    }
                    const id = this.getAttribute('data-id');
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));
                    if (id && previewMarkers[id]) {
                        mapPreview.setView([lat, lng], 16);
                        previewMarkers[id].openPopup();
                        document.getElementById('map-preview').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });

            // Panning ketika tombol preview diklik
            document.querySelectorAll('.preview-location-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));
                    if (id && previewMarkers[id]) {
                        mapPreview.setView([lat, lng], 16);
                        previewMarkers[id].openPopup();
                        document.getElementById('map-preview').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });

            // ==========================================
            // 2. LOGIK PETA TAMBAH LOKASI
            // ==========================================
            let mapAdd = null;
            let markerAdd = null;
            let circleAdd = null;

            function initMapAdd() {
                if (mapAdd) return;

                mapAdd = L.map('map-add').setView([defaultLat, defaultLng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapAdd);

                markerAdd = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapAdd);
                
                const radius = parseFloat(document.getElementById('add-radius-val').value) || 50;
                circleAdd = L.circle([defaultLat, defaultLng], {
                    color: '#6366f1',
                    fillColor: '#6366f1',
                    fillOpacity: 0.18,
                    radius: radius
                }).addTo(mapAdd);

                // Tarik marker
                markerAdd.on('dragend', function() {
                    const pos = markerAdd.getLatLng();
                    circleAdd.setLatLng(pos);
                    updateInputsAdd(pos.lat, pos.lng);
                });

                // Klik peta
                mapAdd.on('click', function(e) {
                    const pos = e.latlng;
                    markerAdd.setLatLng(pos);
                    circleAdd.setLatLng(pos);
                    updateInputsAdd(pos.lat, pos.lng);
                });
            }

            function updateInputsAdd(lat, lng) {
                document.getElementById('add-lat-val').value = lat.toFixed(6);
                document.getElementById('add-lng-val').value = lng.toFixed(6);
            }

            window.syncInputsToMapAdd = function() {
                if (!mapAdd) return;
                const lat = parseFloat(document.getElementById('add-lat-val').value);
                const lng = parseFloat(document.getElementById('add-lng-val').value);
                const radius = parseFloat(document.getElementById('add-radius-val').value) || 50;

                if (!isNaN(lat) && !isNaN(lng)) {
                    const pos = [lat, lng];
                    markerAdd.setLatLng(pos);
                    circleAdd.setLatLng(pos);
                    circleAdd.setRadius(radius);
                    mapAdd.setView(pos);
                }
            };

            document.getElementById('add-lat-val').addEventListener('input', syncInputsToMapAdd);
            document.getElementById('add-lng-val').addEventListener('input', syncInputsToMapAdd);
            document.getElementById('add-radius-val').addEventListener('input', syncInputsToMapAdd);

            UIkit.util.on('#modal-add-location', 'shown', function () {
                initMapAdd();
                mapAdd.invalidateSize();
                syncInputsToMapAdd();
            });

            // ==========================================
            // 3. LOGIK PETA EDIT LOKASI
            // ==========================================
            let mapEdit = null;
            let markerEdit = null;
            let circleEdit = null;

            function initMapEdit(lat, lng, radius) {
                if (mapEdit) {
                    const pos = [lat, lng];
                    mapEdit.setView(pos, 15);
                    markerEdit.setLatLng(pos);
                    circleEdit.setLatLng(pos);
                    circleEdit.setRadius(radius);
                    return;
                }

                mapEdit = L.map('map-edit').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapEdit);

                markerEdit = L.marker([lat, lng], { draggable: true }).addTo(mapEdit);
                circleEdit = L.circle([lat, lng], {
                    color: '#6366f1',
                    fillColor: '#6366f1',
                    fillOpacity: 0.18,
                    radius: radius
                }).addTo(mapEdit);

                markerEdit.on('dragend', function() {
                    const pos = markerEdit.getLatLng();
                    circleEdit.setLatLng(pos);
                    updateInputsEdit(pos.lat, pos.lng);
                });

                mapEdit.on('click', function(e) {
                    const pos = e.latlng;
                    markerEdit.setLatLng(pos);
                    circleEdit.setLatLng(pos);
                    updateInputsEdit(pos.lat, pos.lng);
                });
            }

            function updateInputsEdit(lat, lng) {
                document.getElementById('edit-lat').value = lat.toFixed(6);
                document.getElementById('edit-lng').value = lng.toFixed(6);
            }

            window.syncInputsToMapEdit = function() {
                if (!mapEdit) return;
                const lat = parseFloat(document.getElementById('edit-lat').value);
                const lng = parseFloat(document.getElementById('edit-lng').value);
                const radius = parseFloat(document.getElementById('edit-radius').value) || 50;

                if (!isNaN(lat) && !isNaN(lng)) {
                    const pos = [lat, lng];
                    markerEdit.setLatLng(pos);
                    circleEdit.setLatLng(pos);
                    circleEdit.setRadius(radius);
                    mapEdit.setView(pos);
                }
            };

            document.getElementById('edit-lat').addEventListener('input', syncInputsToMapEdit);
            document.getElementById('edit-lng').addEventListener('input', syncInputsToMapEdit);
            document.getElementById('edit-radius').addEventListener('input', syncInputsToMapEdit);

            // Trigger click button ubah di table
            document.querySelectorAll('.edit-location-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));
                    const radius = parseFloat(this.getAttribute('data-radius'));
                    const active = this.getAttribute('data-active') === '1';

                    // Update endpoint action form
                    document.getElementById('edit-location-form').setAttribute('action', `/admin/school-locations/${id}`);

                    // Isi input form
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-lat').value = lat.toFixed(6);
                    document.getElementById('edit-lng').value = lng.toFixed(6);
                    document.getElementById('edit-radius').value = radius;
                    document.getElementById('edit-active').checked = active;
                    
                    // Bersihkan livesearch lama
                    document.getElementById('search-edit').value = '';
                    const resultsEdit = document.getElementById('results-edit');
                    resultsEdit.innerHTML = '';
                    resultsEdit.classList.add('hidden');

                    // Munculkan Modal
                    UIkit.modal('#modal-edit-location').show();
                });
            });

            UIkit.util.on('#modal-edit-location', 'shown', function () {
                const lat = parseFloat(document.getElementById('edit-lat').value) || defaultLat;
                const lng = parseFloat(document.getElementById('edit-lng').value) || defaultLng;
                const radius = parseFloat(document.getElementById('edit-radius').value) || 50;

                initMapEdit(lat, lng, radius);
                mapEdit.invalidateSize();
                syncInputsToMapEdit();
            });

            // ==========================================
            // 4. LIVE SEARCH NOMINATIM AUTOCOMPLETE
            // ==========================================
            function setupLiveSearch(inputId, resultsId, latId, lngId, nameId, type) {
                const searchInput = document.getElementById(inputId);
                const resultsDiv = document.getElementById(resultsId);
                let debounceTimer = null;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = searchInput.value.trim();

                    if (query.length < 3) {
                        resultsDiv.innerHTML = '';
                        resultsDiv.classList.add('hidden');
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        resultsDiv.innerHTML = '<div class="p-3 text-xs text-slate-400 font-semibold flex items-center space-x-2"><span uk-icon="icon: refresh; ratio: 0.6" class="animate-spin text-indigo-500"></span><span>Mencari lokasi...</span></div>';
                        resultsDiv.classList.remove('hidden');

                        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`;

                        fetch(url, {
                            headers: {
                                'Accept-Language': 'id-ID,id;q=0.9,en;q=0.8'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length === 0) {
                                resultsDiv.innerHTML = '<div class="p-3 text-xs text-slate-400 font-semibold">Lokasi tidak ditemukan.</div>';
                                return;
                            }

                            data.forEach(item => {
                                const address = item.address || {};
                                // Prioritaskan penamaan lokasi yang informatif untuk Nama Gedung
                                const namePart = item.name || address.amenity || address.building || address.road || item.display_name.split(',')[0];
                                const displayName = item.display_name;
                                const lat = parseFloat(item.lat);
                                const lon = parseFloat(item.lon);

                                const div = document.createElement('div');
                                div.className = 'p-3 text-xs hover:bg-slate-50/80 cursor-pointer border-b border-slate-100/70 last:border-0 transition-colors duration-150';
                                div.innerHTML = `
                                    <div class="font-bold text-slate-700 flex items-center space-x-1">
                                        <span uk-icon="icon: location; ratio: 0.6" class="text-indigo-500"></span>
                                        <span>${namePart}</span>
                                    </div>
                                    <div class="text-[9px] text-slate-400 mt-0.5 truncate pl-4">${displayName}</div>
                                `;

                                div.addEventListener('click', function() {
                                    // PENGISIAN DI LAKUKAN KETIKA DI PILIH (Sesuai Request User)
                                    document.getElementById(latId).value = lat.toFixed(6);
                                    document.getElementById(lngId).value = lon.toFixed(6);
                                    document.getElementById(nameId).value = namePart;

                                    // Update visual peta
                                    if (type === 'add') {
                                        syncInputsToMapAdd();
                                    } else {
                                        syncInputsToMapEdit();
                                    }

                                    // Tutup Dropdown
                                    resultsDiv.innerHTML = '';
                                    resultsDiv.classList.add('hidden');
                                    searchInput.value = displayName;
                                });

                                resultsDiv.appendChild(div);
                            });
                        })
                        .catch(err => {
                            console.error('Error Nominatim Search:', err);
                            resultsDiv.innerHTML = '<div class="p-3 text-xs text-rose-500 font-semibold">Koneksi internet bermasalah. Gagal mencari lokasi.</div>';
                        });
                    }, 300);
                });

                // Klik di luar untuk menutup dropdown
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                        resultsDiv.classList.add('hidden');
                    }
                });
            }

            // Inisialisasi input auto-complete
            setupLiveSearch('search-add', 'results-add', 'add-lat-val', 'add-lng-val', 'add-name', 'add');
            setupLiveSearch('search-edit', 'results-edit', 'edit-lat', 'edit-lng', 'edit-name', 'edit');

            // ==========================================
            // 5. METODE GEOLOKASI (LOKASI SEKARANG)
            // ==========================================
            window.useCurrentLocation = function(type) {
                if (!navigator.geolocation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Browser Tidak Didukung',
                        text: 'Geolocation tidak didukung oleh browser Anda.',
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                const btn = event.currentTarget;
                const origHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span uk-icon="icon: refresh; ratio: 0.75" class="animate-spin text-slate-600 mr-1"></span><span>Mencari Posisi...</span>';

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;

                        if (type === 'add') {
                            document.getElementById('add-lat-val').value = lat.toFixed(6);
                            document.getElementById('add-lng-val').value = lng.toFixed(6);
                            syncInputsToMapAdd();
                        } else {
                            document.getElementById('edit-lat').value = lat.toFixed(6);
                            document.getElementById('edit-lng').value = lng.toFixed(6);
                            syncInputsToMapEdit();
                        }

                        btn.disabled = false;
                        btn.innerHTML = origHtml;
                    },
                    function(err) {
                        btn.disabled = false;
                        btn.innerHTML = origHtml;
                        let msg = 'Gagal mendeteksi lokasi.';
                        if (err.code === err.PERMISSION_DENIED) {
                            msg = 'Akses ke GPS Anda ditolak. Silakan aktifkan izin lokasi di browser Anda.';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mendeteksi Lokasi',
                            text: msg,
                            confirmButtonColor: '#4f46e5'
                        });
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
                );
            };
        });
    </script>
</x-app-layout>


