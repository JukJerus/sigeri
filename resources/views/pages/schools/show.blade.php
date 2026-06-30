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
                <a href="{{ route('schools.index') }}"
                    class="rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                    Kembali
                </a>
                @auth
                    @if (Auth::user()->canAccessSekolah($school->id))
                        <a href="{{ route('schools.edit', $school->id) }}"
                            class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor">
                                <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                            </svg>
                            Edit Data
                        </a>
                        <a href="{{ route('galeri.create', $school->id) }}"
                            class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor">
                                <path
                                    d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" />
                                <path
                                    d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>
                            Upload Foto
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Success alert --}}
        @if (session('success'))
            <div
                class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <svg viewBox="0 0 20 20" class="h-5 w-5 shrink-0 fill-emerald-500">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-6 pt-8">
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
                <div class="border-b border-black/10 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Profil Sekolah</h2>
                </div>
                <div class="space-y-6 p-6">
                    {{-- Carousel Foto Sekolah --}}
                    @php $fotoSekolah = $school->galeri->where('tipe', 'sekolah'); @endphp
                    <div class="relative h-[260px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]"
                        x-data="carousel({{ $fotoSekolah->count() }})" x-cloak>
                        @if ($fotoSekolah->count() > 0)
                            <div class="relative h-full">
                                @foreach ($fotoSekolah as $i => $foto)
                                    <div x-show="active === {{ $i }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute inset-0">
                                        <img src="{{ asset('storage/' . $foto->file_foto) }}"
                                            alt="{{ $foto->caption ?: 'Foto sekolah' }}"
                                            class="h-full w-full object-cover" />
                                        @if ($foto->caption)
                                            <div
                                                class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent px-4 pb-3 pt-8">
                                                <p class="text-sm font-medium text-white">{{ $foto->caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                {{-- Nav buttons --}}
                                @if ($fotoSekolah->count() > 1)
                                    <button @click="prev()"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow-sm backdrop-blur transition hover:bg-white">
                                        <svg viewBox="0 0 20 20" class="h-4 w-4 text-slate-700" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button @click="next()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow-sm backdrop-blur transition hover:bg-white">
                                        <svg viewBox="0 0 20 20" class="h-4 w-4 text-slate-700" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                                        @foreach ($fotoSekolah as $i => $foto)
                                            <button @click="active = {{ $i }}"
                                                :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'"
                                                class="h-2 w-2 rounded-full transition"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div
                                class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                            </div>
                            <div class="relative flex h-full items-center justify-center text-center">
                                <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                                    <p class="text-sm font-semibold text-slate-700">Foto Sekolah</p>
                                    <p class="mt-1 text-xs text-slate-500">Belum ada foto yang diupload.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                            <p class="mt-2 text-sm font-medium text-slate-900">{{ $school->alamat }}, Kec.
                                {{ $school->kecamatan->nama ?? '-' }}, Kel. {{ $school->kelurahan->nama ?? '-' }}</p>
                        </div>
                        
                        <hr class="border-black/5" />

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-1">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">NPSN</p>
                                <p class="font-semibold text-slate-900">{{ $school->npsn }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Operator Sekolah</p>
                                @if($school->operator)
                                    <p class="font-semibold text-slate-900">{{ $school->operator->nama }}</p>
                                    @if($school->operator->telepon)
                                        @php
                                            $telepon = $school->operator->telepon;
                                            $cleanNumber = preg_replace('/[^0-9]/', '', $telepon);
                                            if (str_starts_with($cleanNumber, '0')) {
                                                $cleanNumber = '62' . substr($cleanNumber, 1);
                                            }
                                            $waUrl = "https://wa.me/{$cleanNumber}";
                                        @endphp
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <!-- WhatsApp Link -->
                                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.588 2.01 14.12 1.01 11.48 1.01c-5.442 0-9.866 4.372-9.87 9.802 0 1.772.487 3.502 1.412 5.065L1.96 20.54l4.687-1.386z"/>
                                                    <path d="M17.486 14.28c-.3-.15-1.774-.875-2.05-.975-.275-.1-.475-.15-.675.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.263-.465-2.404-1.485-.888-.79-1.487-1.77-1.662-2.07-.175-.3-.02-.46.13-.61.137-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.493-.51-.675-.52-.175-.01-.375-.01-.575-.01-.2 0-.525.075-.8 1.025-.275.95-1.05 3.1-1.05 3.2 0 .1.1.2.223.337.525.59 1.565 1.72 3.39 2.51.84.36 1.5.58 2.01.74.84.27 1.6.23 2.2.14.67-.1 1.774-.725 2.025-1.39.25-.665.25-1.24.175-1.39-.075-.15-.275-.25-.575-.4z"/>
                                                </svg>
                                                Chat WA
                                            </a>
                                            <!-- Fallback: Direct Phone Call -->
                                            <a href="tel:{{ $telepon }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-200"
                                                title="Jika WhatsApp tidak aktif, hubungi via telepon biasa">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                Telepon
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-xs text-slate-400">Nomor telepon tidak tersedia</p>
                                    @endif
                                @else
                                    <p class="font-medium text-slate-500">Belum ditugaskan</p>
                                @endif
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
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school->jumlah_siswa_perempuan }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Siswa</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">
                                {{ $school->jumlah_siswa_laki + $school->jumlah_siswa_perempuan }}</p>
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
                    <span
                        class="text-xs text-slate-500">Koordinat
                        {{ $school->latitude ? $school->latitude . ', ' . $school->longitude : 'belum tersedia' }}</span>
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

    {{-- Fasilitas + Carousel Foto Fasilitas --}}
    <section class="mx-auto max-w-6xl px-6 pb-16">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Fasilitas Sekolah</h2>
            </div>
            <div class="grid gap-6 p-6 lg:grid-cols-2">
                {{-- Data Fasilitas --}}
                <div
                    class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700 content-start">
                    @if ($school->fasilitas)
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
                            <span
                                class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_lab_komputer }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Lab IPA</span>
                            <span class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_lab_ipa }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Ruang Kepsek</span>
                            <span
                                class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_ruang_kepsek }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Ruang Guru</span>
                            <span
                                class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_ruang_guru }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">WC Siswa (L/P)</span>
                            <span
                                class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_wcs_laki }}
                                / {{ $school->fasilitas->jumlah_wcs_perempuan }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">WC Guru (L/P)</span>
                            <span
                                class="font-semibold text-slate-900">{{ $school->fasilitas->jumlah_wcg_laki }}
                                / {{ $school->fasilitas->jumlah_wcg_perempuan }}</span>
                        </div>
                    @else
                        <div class="text-center text-slate-500 py-4">Data fasilitas tidak tersedia</div>
                    @endif
                </div>

                {{-- Carousel Foto Fasilitas --}}
                @php $fotoFasilitas = $school->galeri->where('tipe', 'fasilitas'); @endphp
                <div class="relative min-h-[280px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]"
                    x-data="carousel({{ $fotoFasilitas->count() }})" x-cloak>
                    @if ($fotoFasilitas->count() > 0)
                        <div class="relative h-full min-h-[280px]">
                            @foreach ($fotoFasilitas as $i => $foto)
                                <div x-show="active === {{ $i }}"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute inset-0">
                                    <img src="{{ asset('storage/' . $foto->file_foto) }}"
                                        alt="{{ $foto->caption ?: 'Foto fasilitas' }}"
                                        class="h-full w-full object-cover" />
                                    <div
                                        class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent px-4 pb-3 pt-8">
                                        <p class="text-sm font-medium text-white">
                                            {{ $foto->caption ?: 'Foto Fasilitas' }}</p>
                                        @auth
                                            @if (Auth::user()->canAccessSekolah($school->id))
                                                <form action="{{ route('galeri.destroy', $foto->id) }}" method="POST"
                                                    class="mt-1" onsubmit="return confirm('Hapus foto ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-xs text-white/80 underline hover:text-white">Hapus
                                                        foto</button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach

                            @if ($fotoFasilitas->count() > 1)
                                <button @click="prev()"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow-sm backdrop-blur transition hover:bg-white">
                                    <svg viewBox="0 0 20 20" class="h-4 w-4 text-slate-700" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="next()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow-sm backdrop-blur transition hover:bg-white">
                                    <svg viewBox="0 0 20 20" class="h-4 w-4 text-slate-700" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                                    @foreach ($fotoFasilitas as $i => $foto)
                                        <button @click="active = {{ $i }}"
                                            :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'"
                                            class="h-2 w-2 rounded-full transition"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                        </div>
                        <div class="relative flex h-full min-h-[280px] items-center justify-center text-center">
                            <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                                <p class="text-sm font-semibold text-slate-700">Foto Fasilitas</p>
                                <p class="mt-1 text-xs text-slate-500">Belum ada foto fasilitas yang diupload.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function carousel(total) {
            return {
                active: 0,
                total: total,
                next() {
                    this.active = (this.active + 1) % this.total;
                },
                prev() {
                    this.active = (this.active - 1 + this.total) % this.total;
                }
            }
        }
    </script>
@endpush
