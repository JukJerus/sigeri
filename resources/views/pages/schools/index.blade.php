@extends('layouts.app')

@section('title', 'Daftar Sekolah - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Data Sekolah</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                    Daftar SD Negeri di Kota Bekasi
                </h1>
                <p class="text-sm text-slate-600">
                    Menampilkan data sekolah berdasarkan kecamatan beserta informasi dasar.
                </p>
            </div>
            @auth
                @if (Auth::user()->isAdmin())
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('schools.export', request()->only(['search', 'kecamatan', 'akreditasi'])) }}"
                            class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20 active:scale-[0.98]">
                            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>
                            Ekspor CSV
                        </a>
                        <a href="{{ route('schools.create') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                            <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                            </svg>
                            Tambah Sekolah
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <svg viewBox="0 0 20 20" class="h-5 w-5 shrink-0 fill-emerald-500">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter Bar --}}
        <form action="{{ route('schools.index') }}" method="GET" class="mt-8 flex flex-col gap-3 sm:flex-row">
            {{-- Search --}}
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <svg viewBox="0 0 20 20" class="h-4 w-4 shrink-0 text-slate-400" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                        clip-rule="evenodd" />
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NPSN..."
                    class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
            </label>

            {{-- Kecamatan --}}
            <label
                class="flex items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">Kecamatan</span>
                <select name="kecamatan" onchange="this.form.submit()"
                    class="bg-transparent text-sm font-medium text-slate-700 focus:outline-none pr-6 appearance-none cursor-pointer">
                    <option value="">Semua</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec->id }}"
                            {{ ($kecamatanId ?? '') == $kec->id ? 'selected' : '' }}>
                            {{ $kec->nama }}
                        </option>
                    @endforeach
                </select>
            </label>

            {{-- Akreditasi --}}
            <label
                class="flex items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">Akreditasi</span>
                <select name="akreditasi" onchange="this.form.submit()"
                    class="bg-transparent text-sm font-medium text-slate-700 focus:outline-none pr-6 appearance-none cursor-pointer">
                    <option value="">Semua</option>
                    @foreach (['A', 'B', 'C'] as $a)
                        <option value="{{ $a }}" {{ ($akreditasi ?? '') === $a ? 'selected' : '' }}>
                            {{ $a }}
                        </option>
                    @endforeach
                </select>
            </label>

            {{-- Reset --}}
            @if (($search ?? '') || ($kecamatanId ?? '') || ($akreditasi ?? ''))
                <a href="{{ route('schools.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-black/20 whitespace-nowrap">
                    <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor">
                        <path
                            d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                    Reset
                </a>
            @endif

            <button type="submit" class="hidden">Cari</button>
        </form>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-black/10 px-6 py-4 text-xs text-slate-500">
                <span class="font-semibold uppercase tracking-[0.2em]">Jumlah data: {{ $schools->total() }}</span>
                @if ($kecamatanId || $akreditasi || $search)
                    <span class="text-slate-400">
                        Filter aktif:
                        @if ($kecamatanId)
                            Kec. {{ $kecamatans->firstWhere('id', $kecamatanId)?->nama }}{{ $akreditasi || $search ? ',' : '' }}
                        @endif
                        @if ($akreditasi)
                            Akreditasi {{ $akreditasi }}{{ $search ? ',' : '' }}
                        @endif
                        @if ($search)
                            "{{ $search }}"
                        @endif
                    </span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] border-collapse text-left text-sm">
                    <thead class="border-b border-black/10 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Nama Sekolah</th>
                            <th class="px-6 py-4">NPSN</th>
                            <th class="px-6 py-4">Kecamatan</th>
                            <th class="px-6 py-4">Kelurahan</th>
                            <th class="px-6 py-4">Akreditasi</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse($schools as $school)
                            <tr class="border-b border-black/5 hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $school->nama }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500 truncate max-w-[250px]">
                                        {{ $school->alamat }}</p>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $school->npsn }}</td>
                                <td class="px-6 py-4">{{ $school->kecamatan->nama ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $school->kelurahan->nama ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($school->akreditasi)
                                        @php
                                            $aColors = [
                                                'A' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                                'B' => 'bg-sky-50 border-sky-200 text-sky-700',
                                                'C' => 'bg-amber-50 border-amber-200 text-amber-700',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $aColors[$school->akreditasi] ?? 'bg-slate-100 border-slate-200 text-slate-700' }}">
                                            {{ $school->akreditasi }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('schools.show', $school->id) }}"
                                            class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20 whitespace-nowrap">
                                            Detail
                                        </a>
                                        @auth
                                            @if (Auth::user()->canAccessSekolah($school->id))
                                                <a href="{{ route('schools.edit', $school->id) }}"
                                                    class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20 whitespace-nowrap">
                                                    Edit
                                                </a>
                                            @endif
                                            @if (Auth::user()->isAdmin())
                                                <form action="{{ route('schools.destroy', $school->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus sekolah {{ $school->nama }}? Semua data terkait (fasilitas, laporan, galeri) akan ikut terhapus.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100 whitespace-nowrap">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-slate-400 text-center" colspan="6">
                                    Data sekolah tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-black/10">
                {{ $schools->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
@endsection
