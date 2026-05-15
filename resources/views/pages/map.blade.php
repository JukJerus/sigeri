@extends('layouts.app')

@section('title', 'Peta - SIGERI')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl space-y-3">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">WebGIS Kota Bekasi</p>
                <h1 class="text-4xl font-semibold tracking-tight text-slate-900 md:text-5xl">
                    Peta Sebaran SD Negeri
                </h1>
                <p class="text-base text-slate-600">
                    Visualisasi lokasi sekolah dasar negeri dan ringkasan fasilitas pendidikan. Marker sekolah
                    akan ditambahkan setelah data terhubung.
                </p>
            </div>
            <div class="flex w-full flex-col gap-3 sm:flex-row md:w-auto">
                <label
                    class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/70 px-4 py-2 shadow-sm">
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kecamatan</span>
                    <select class="w-full bg-transparent text-sm font-medium text-slate-700 focus:outline-none">
                        <option>Semua Kecamatan</option>
                    </select>
                </label>
                <label
                    class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/70 px-4 py-2 shadow-sm">
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Cari</span>
                    <input type="text" placeholder="Nama sekolah"
                        class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
                </label>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="grid gap-6 lg:grid-cols-[1.6fr_0.7fr]">
            <div class="rounded-3xl border border-black/10 bg-white/70 shadow-lg backdrop-blur animate-rise-in">
                <div class="relative h-[420px] w-full overflow-hidden rounded-3xl md:h-[520px]">
                    <div id="map" class="h-full w-full"></div>
                    <div
                        class="absolute right-4 top-4 rounded-full border border-black/10 bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm">
                        OpenStreetMap
                    </div>
                    <div class="absolute left-4 top-4 rounded-2xl border border-black/10 bg-white/90 px-4 py-3 shadow-sm">
                        <p class="text-xs font-semibold text-slate-700">Peta default</p>
                        <p class="mt-1 text-xs text-slate-500">Marker sekolah akan ditambahkan.</p>
                    </div>
                    <div
                        class="absolute bottom-4 left-4 flex items-center gap-2 rounded-full border border-black/10 bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Zona Bekasi
                    </div>
                </div>
                <div
                    class="flex flex-col gap-3 border-t border-black/10 px-6 py-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                    <span>Filter: Semua kecamatan</span>
                    <span>Zoom: 11</span>
                </div>
            </div>

            <aside class="rounded-3xl border border-black/10 bg-white/70 p-6 shadow-sm animate-rise-in">
                <h2 class="text-lg font-semibold text-slate-900">Ringkasan cepat</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Gambaran awal data sekolah sebelum marker aktif.
                </p>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between rounded-2xl border border-black/10 bg-white px-4 py-3">
                        <span class="text-sm text-slate-600">Total SD Negeri</span>
                        <span class="text-lg font-semibold text-slate-900">320</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-black/10 bg-white px-4 py-3">
                        <span class="text-sm text-slate-600">Kecamatan</span>
                        <span class="text-lg font-semibold text-slate-900">12</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-black/10 bg-white px-4 py-3">
                        <span class="text-sm text-slate-600">Kelurahan</span>
                        <span class="text-lg font-semibold text-slate-900">56</span>
                    </div>
                </div>
                <div class="mt-6 rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-slate-600">
                    Tip: gunakan filter kecamatan untuk mempersempit tampilan peta.
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map', {
                zoomControl: true
            }).setView([-6.2383, 106.9756], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const bekasiBoundary = {
                type: 'FeatureCollection',
                name: 'Batas_Administrasi_Kota_Bekasi',
                crs: {
                    type: 'name',
                    properties: {
                        name: 'urn:ogc:def:crs:OGC:1.3:CRS84',
                    },
                },
                features: [{
                    type: 'Feature',
                    properties: {
                        OBJECTID: 1,
                        WADMKC: 'Kota Bekasi',
                        WADMKD: 'Jawa Barat',
                        Batas_Utara: 'Kabupaten Bekasi',
                        Batas_Selatan: 'Kabupaten Bogor dan Kota Depok',
                        Batas_Barat: 'Provinsi DKI Jakarta',
                        Batas_Timur: 'Kabupaten Bekasi',
                        Luas_km2: 210.49,
                    },
                    geometry: {
                        type: 'Polygon',
                        coordinates: [
                            [
                                [106.945, -6.166],
                                [107.032, -6.166],
                                [107.032, -6.22],
                                [107.05, -6.25],
                                [107.015, -6.368],
                                [106.92, -6.368],
                                [106.9, -6.33],
                                [106.9, -6.24],
                                [106.945, -6.166],
                            ],
                        ],
                    },
                }, ],
            };

            const boundaryLayer = L.geoJSON(bekasiBoundary, {
                style: {
                    color: '#0f172a',
                    weight: 2,
                    dashArray: '6 6',
                    fillColor: '#38bdf8',
                    fillOpacity: 0.08,
                },
            }).addTo(map);

            map.fitBounds(boundaryLayer.getBounds(), {
                padding: [24, 24]
            });
        });
    </script>
@endpush
