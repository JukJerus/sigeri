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

<div class="grid gap-6 sm:grid-cols-2" x-data="{
    kecamatanId: '{{ old('kecamatan_id', $s ? ($s->kelurahan->kecamatan_id ?? '') : '') }}',
    kelurahanId: '{{ old('kelurahan_id', $s->kelurahan_id ?? '') }}',
    kecamatans: {{ Js::from($kecamatans) }},
    get kelurahans() {
        const kec = this.kecamatans.find(k => k.id == this.kecamatanId);
        return kec ? kec.kelurahan : [];
    }
}">
    <div>
        <label for="kecamatan_id" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Kecamatan <span class="text-red-400">*</span>
        </label>
        <select id="kecamatan_id" x-model="kecamatanId" @change="kelurahanId = ''"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="">-- Pilih Kecamatan --</option>
            <template x-for="kec in kecamatans" :key="kec.id">
                <option :value="kec.id" x-text="kec.nama" :selected="kec.id == kecamatanId"></option>
            </template>
        </select>
    </div>
    <div>
        <label for="kelurahan_id" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Kelurahan <span class="text-red-400">*</span>
        </label>
        <select id="kelurahan_id" name="kelurahan_id" x-model="kelurahanId" required
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <option value="">-- Pilih Kelurahan --</option>
            <template x-for="kel in kelurahans" :key="kel.id">
                <option :value="kel.id" x-text="kel.nama" :selected="kel.id == kelurahanId"></option>
            </template>
        </select>
    </div>
</div>

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
            value="{{ old('latitude', $s->latitude ?? '') }}" placeholder="-6.xxxxxxxx"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
    </div>
    <div>
        <label for="longitude" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
            Longitude
        </label>
        <input id="longitude" name="longitude" type="number" step="0.00000001"
            value="{{ old('longitude', $s->longitude ?? '') }}" placeholder="106.xxxxxxxx"
            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
    </div>
</div>

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
