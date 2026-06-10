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

        </div>

        <form action="{{ route('schools.index') }}" method="GET" class="mt-8 flex flex-col gap-3 sm:flex-row">
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Search</span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama sekolah / NPSN"
                    class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
                <button type="submit" class="hidden">Cari</button>
            </label>
        </form>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-black/10 px-6 py-4 text-xs text-slate-500">
                <span class="font-semibold uppercase tracking-[0.2em]">Jumlah data: {{ $schools->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] border-collapse text-left text-sm">
                    <thead class="border-b border-black/10 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Nama Sekolah</th>
                            <th class="px-6 py-4">NPSN</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Kelurahan</th>
                            <th class="px-6 py-4">Kecamatan</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse($schools as $school)
                            <tr class="border-b border-black/5 hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $school->nama }}</td>
                                <td class="px-6 py-4">{{ $school->npsn }}</td>
                                <td class="px-6 py-4 truncate max-w-[200px]">{{ $school->alamat }}</td>
                                <td class="px-6 py-4">{{ $school->kelurahan->nama ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $school->kecamatan->nama ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('schools.show', $school->id) }}"
                                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20 whitespace-nowrap">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-slate-400 text-center" colspan="6">
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
