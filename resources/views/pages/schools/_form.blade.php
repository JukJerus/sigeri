{{-- Shared form partial for create & edit --}}
{{-- Expects: $school (nullable), $kecamatans --}}
@php
    $s = $school ?? null;
    $f = $s?->fasilitas;
@endphp

{{-- ── Informasi Dasar ─────────────────────── --}}
<div class="space-y-1">
    <h3 class="text-sm font-semibold text-slate-900">Informasi Dasar</h3>
    <p class="text-xs text-slate-500">Data identitas sekolah.</p>
</div>

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <label for="nama" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Nama Sekolah <span class="text-red-400">*</span>
        </label>
        <input id="nama" name="nama" type="text" value="{{ old('nama', $s->nama ?? '') }}" required
            placeholder="SD Negeri ..."
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
    </div>
    <div>
        <label for="npsn" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            NPSN <span class="text-red-400">*</span>
        </label>
        <input id="npsn" name="npsn" type="text" value="{{ old('npsn', $s->npsn ?? '') }}" required
            placeholder="20xxxxxx"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
    </div>
</div>

<div>
    <label for="alamat" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
        Alamat
    </label>
    <textarea id="alamat" name="alamat" rows="2" placeholder="Jl. ..."
        class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('alamat', $s->alamat ?? '') }}</textarea>
</div>

<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <label for="kecamatan_id" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Kecamatan <span class="text-red-400">*</span>
        </label>
        <select id="kecamatan_id"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($kecamatans as $kec)
                <option value="{{ $kec->id }}"
                    {{ old('kecamatan_id', $s ? ($s->kelurahan->kecamatan_id ?? '') : '') == $kec->id ? 'selected' : '' }}>
                    {{ $kec->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="kelurahan_id" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Kelurahan <span class="text-red-400">*</span>
        </label>
        <select id="kelurahan_id" name="kelurahan_id" required
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="">-- Pilih Kelurahan --</option>
        </select>
    </div>
</div>

<script>
    (function() {
        const kecamatans = @json($kecamatans);
        const initialKelurahanId = '{{ old('kelurahan_id', $s->kelurahan_id ?? '') }}';
        const kecSelect = document.getElementById('kecamatan_id');
        const kelSelect = document.getElementById('kelurahan_id');

        function populateKelurahan(kecId, selectedKelId) {
            kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!kecId) return;
            const kec = kecamatans.find(k => k.id == kecId);
            if (kec && kec.kelurahan) {
                kec.kelurahan.forEach(kel => {
                    const opt = document.createElement('option');
                    opt.value = kel.id;
                    opt.textContent = kel.nama;
                    if (kel.id == selectedKelId) opt.selected = true;
                    kelSelect.appendChild(opt);
                });
            }
        }

        // Populate on page load
        populateKelurahan(kecSelect.value, initialKelurahanId);

        // Re-populate on kecamatan change
        kecSelect.addEventListener('change', function() {
            populateKelurahan(this.value, '');
        });
    })();
</script>

<div class="grid gap-6 sm:grid-cols-3">
    <div>
        <label for="akreditasi" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Akreditasi
        </label>
        <select id="akreditasi" name="akreditasi"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="">-</option>
            @foreach (['A', 'B', 'C'] as $a)
                <option value="{{ $a }}" {{ old('akreditasi', $s->akreditasi ?? '') === $a ? 'selected' : '' }}>
                    {{ $a }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="latitude" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Latitude
        </label>
        <input id="latitude" name="latitude" type="number" step="0.00000001"
            value="{{ old('latitude', $s->latitude ?? '') }}" placeholder="-6.xxxxxxxx" readonly
            class="w-full rounded-xl border border-black/10 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 cursor-not-allowed" />
    </div>
    <div>
        <label for="longitude" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Longitude
        </label>
        <input id="longitude" name="longitude" type="number" step="0.00000001"
            value="{{ old('longitude', $s->longitude ?? '') }}" placeholder="106.xxxxxxxx" readonly
            class="w-full rounded-xl border border-black/10 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 cursor-not-allowed" />
    </div>
</div>

{{-- ── Geocoding & Mini Map ────────────────── --}}
<div class="space-y-3">
    <div class="flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-0">
            <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                Cari Koordinat dari Alamat
            </label>
            <p class="text-xs text-slate-400">Klik tombol untuk mencari lokasi berdasarkan alamat, atau geser marker di peta secara manual.</p>
        </div>
        <button type="button" id="btn-geocode"
            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-[0.98] shrink-0">
            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
            </svg>
            <span id="btn-geocode-text">Cari di Peta</span>
        </button>
    </div>

    {{-- Status indicator --}}
    <div id="geocode-status" class="hidden rounded-xl border px-4 py-2.5 text-sm font-medium"></div>

    {{-- Mini Map --}}
    <div class="overflow-hidden rounded-2xl border border-black/10 shadow-sm">
        <div id="geocode-map" style="height: 320px; width: 100%; z-index: 0;"></div>
    </div>
    <p class="text-xs text-slate-400 text-center">💡 Geser marker (pin) untuk menyesuaikan posisi secara manual. Koordinat akan otomatis terupdate.</p>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
(function () {
    // Default: Kota Bekasi center
    const defaultLat = {{ old('latitude', $s->latitude ?? '-6.2383') }};
    const defaultLng = {{ old('longitude', $s->longitude ?? '106.9756') }};
    const hasCoords  = {{ ($s->latitude ?? false) ? 'true' : 'false' }};

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const btnGeocode = document.getElementById('btn-geocode');
    const btnText = document.getElementById('btn-geocode-text');
    const statusEl = document.getElementById('geocode-status');

    // Initialize map
    const map = L.map('geocode-map', {
        center: [defaultLat, defaultLng],
        zoom: hasCoords ? 17 : 13,
        scrollWheelZoom: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Custom icon
    const pinIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    });

    // Create draggable marker
    const marker = L.marker([defaultLat, defaultLng], {
        draggable: true,
        icon: pinIcon,
    }).addTo(map);

    // Update inputs when marker is dragged
    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
        showStatus('success', '✅ Koordinat diperbarui: ' + pos.lat.toFixed(6) + ', ' + pos.lng.toFixed(6));
    });

    // Fix map rendering inside hidden/animated containers
    setTimeout(() => map.invalidateSize(), 300);

    // Geocode button click
    btnGeocode.addEventListener('click', async function () {
        const alamat = document.getElementById('alamat')?.value?.trim() || '';
        const kecEl = document.getElementById('kecamatan_id');
        const kelEl = document.getElementById('kelurahan_id');
        const kecNama = kecEl?.selectedOptions[0]?.textContent?.trim() || '';
        const kelNama = kelEl?.selectedOptions[0]?.textContent?.trim() || '';
        const namaSekolah = document.getElementById('nama')?.value?.trim() || '';

        // Build search query: combine address parts for better accuracy
        let queryParts = [];
        if (namaSekolah) queryParts.push(namaSekolah);
        if (alamat) queryParts.push(alamat);
        if (kelNama && kelNama !== '-- Pilih Kelurahan --') queryParts.push(kelNama);
        if (kecNama && kecNama !== '-- Pilih Kecamatan --') queryParts.push(kecNama);
        queryParts.push('Kota Bekasi');

        const query = queryParts.join(', ');

        if (!alamat && !namaSekolah) {
            showStatus('warning', '⚠️ Isi alamat atau nama sekolah terlebih dahulu sebelum mencari koordinat.');
            return;
        }

        // Show loading
        btnGeocode.disabled = true;
        btnText.textContent = 'Mencari...';
        showStatus('info', '🔍 Mencari lokasi: "' + query + '"...');

        try {
            const url = 'https://nominatim.openstreetmap.org/search?'
                + new URLSearchParams({
                    q: query,
                    format: 'json',
                    limit: '1',
                    countrycodes: 'id',
                }).toString();

            const res = await fetch(url, {
                headers: { 'Accept-Language': 'id' },
            });
            const data = await res.json();

            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                const displayName = data[0].display_name || '';

                // Update inputs
                latInput.value = lat.toFixed(8);
                lngInput.value = lng.toFixed(8);

                // Move map & marker
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 17);

                showStatus('success', '✅ Lokasi ditemukan: ' + displayName.substring(0, 100) + (displayName.length > 100 ? '...' : '') + '<br><span class="text-xs text-slate-500">Geser marker jika posisi kurang tepat.</span>');
            } else {
                showStatus('warning', '⚠️ Lokasi tidak ditemukan untuk alamat tersebut. Coba geser marker secara manual, atau ubah/perjelas alamat lalu cari ulang.');
            }
        } catch (err) {
            showStatus('error', '❌ Gagal menghubungi layanan geocoding. Periksa koneksi internet Anda.');
            console.error('Geocoding error:', err);
        } finally {
            btnGeocode.disabled = false;
            btnText.textContent = 'Cari di Peta';
        }
    });

    function showStatus(type, message) {
        statusEl.classList.remove('hidden');
        const styles = {
            success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
            warning: 'border-amber-200 bg-amber-50 text-amber-700',
            error:   'border-red-200 bg-red-50 text-red-700',
            info:    'border-sky-200 bg-sky-50 text-sky-700',
        };
        statusEl.className = 'rounded-xl border px-4 py-2.5 text-sm font-medium ' + (styles[type] || styles.info);
        statusEl.innerHTML = message;
    }
})();
</script>

<hr class="border-black/10" />

{{-- ── Data Sekolah ────────────────────────── --}}
<div class="space-y-1">
    <h3 class="text-sm font-semibold text-slate-900">Data Sekolah</h3>
    <p class="text-xs text-slate-500">Jumlah siswa, guru, tenaga kependidikan, dan rombongan belajar.</p>
</div>

@php
    $sekolahFields = [
        ['jumlah_rombel', 'Rombel'],
        ['jumlah_siswa_laki', 'Siswa (L)'],
        ['jumlah_siswa_perempuan', 'Siswa (P)'],
        ['jumlah_guru', 'Guru'],
        ['jumlah_tendik', 'Tendik'],
    ];
@endphp

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
    @foreach ($sekolahFields as [$field, $label])
        <div>
            <label for="{{ $field }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">
                {{ $label }}
            </label>
            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0"
                value="{{ old($field, $s?->$field ?? '') }}"
                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
        </div>
    @endforeach
</div>

<hr class="border-black/10" />

{{-- ── Data Fasilitas ──────────────────────── --}}
<div class="space-y-1">
    <h3 class="text-sm font-semibold text-slate-900">Fasilitas Sekolah</h3>
    <p class="text-xs text-slate-500">Jumlah ruang dan fasilitas yang tersedia.</p>
</div>

@php
    $fasilitasFields = [
        ['jumlah_kelas', 'Ruang Kelas'],
        ['jumlah_perpustakaan', 'Perpustakaan'],
        ['jumlah_lab_komputer', 'Lab Komputer'],
        ['jumlah_lab_ipa', 'Lab IPA'],
        ['jumlah_ruang_kepsek', 'R. Kepsek'],
        ['jumlah_ruang_guru', 'R. Guru'],
        ['jumlah_ruang_tu', 'R. TU'],
        ['jumlah_wcg_laki', 'WC Guru (L)'],
        ['jumlah_wcg_perempuan', 'WC Guru (P)'],
        ['jumlah_wcs_laki', 'WC Siswa (L)'],
        ['jumlah_wcs_perempuan', 'WC Siswa (P)'],
    ];
@endphp

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
    @foreach ($fasilitasFields as [$field, $label])
        <div>
            <label for="{{ $field }}" class="mb-2 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">
                {{ $label }}
            </label>
            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0"
                value="{{ old($field, $f?->$field ?? '') }}"
                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
        </div>
    @endforeach
</div>
