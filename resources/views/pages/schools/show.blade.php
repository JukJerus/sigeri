@extends('layouts.app')

@section('title', 'Detail Sekolah - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Detail Sekolah</p>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                        {{ $school->nama }}
                    </h1>
                    <span
                        class="rounded-full border border-black/10 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                        Akreditasi {{ $school->akreditasi ?: '-' }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-slate-600">Last updated: {{ $school->updated_at->format('d M Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                    class="rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                    Kembali
                </button>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-6 pt-8">
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
                <div class="border-b border-black/10 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Profil Sekolah</h2>
                    </div>
                </div>
                <div class="space-y-6 p-6">
                    <div class="relative h-[260px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                        </div>
                        <div class="relative flex h-full items-center justify-center text-center">
                            <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                                <p class="text-sm font-semibold text-slate-700">Foto Sekolah</p>
                                <p class="mt-1 text-xs text-slate-500">Carousel foto akan ditampilkan di sini.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                            <p class="mt-2 text-sm font-medium text-slate-900">{{ $school->alamat }}, Kec. {{ $school->kecamatan->nama ?? '-' }}, Kel. {{ $school->kelurahan->nama ?? '-' }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Nomor Telepon</span>
                                <span class="font-semibold text-slate-900">{{ $school->phone ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-500">NPSN</span>
                                <span class="font-semibold text-slate-900">{{ $school->npsn }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Operator</span>
                                <span class="font-semibold text-slate-900">{{ $school->operator->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
                <div class="border-b border-black/10 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Ringkasan Data</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Jumlah Guru</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_guru }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Tenaga Pendidik</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_tendik }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Siswa Laki-laki</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_siswa_laki }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Siswa Perempuan</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_siswa_perempuan }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Siswa</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_siswa_laki + $school->jumlah_siswa_perempuan }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Rombel</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_rombel }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-6">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Lokasi Sekolah</h2>
                    <span class="text-xs text-slate-500">Koordinat {{ $school->latitude ? $school->latitude . ', ' . $school->longitude : 'belum tersedia' }}</span>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-[240px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]">
                    <div class="relative flex h-full items-center justify-center text-center">
                        <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-700">Peta Lokasi</p>
                            <p class="mt-1 text-xs text-slate-500">Peta interaktif belum diaktifkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Fasilitas Sekolah</h2>
                </div>
            </div>
            <div class="grid gap-6 p-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700">
                    @if($school->fasilitas)
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Ruang Kelas</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_kelas }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Perpustakaan</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_perpustakaan }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Lab Komputer</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_lab_komputer }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Lab IPA</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_lab_ipa }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">WC Siswa (L/P)</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_wcs_laki }} / {{ $school->fasilitas->jumlah_wcs_perempuan }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">WC Guru (L/P)</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_wcg_laki }} / {{ $school->fasilitas->jumlah_wcg_perempuan }}</span>
                        </div>
                    @else
                        <div class="text-center text-slate-500 py-4">Data fasilitas tidak tersedia</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
