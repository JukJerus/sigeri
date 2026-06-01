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
            <div class="flex flex-wrap items-center gap-3">
                <button type="button"
                    class="rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                    Ekspor
                </button>
                <button type="button"
                    class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    Tambah
                </button>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kecamatan</span>
                <select class="w-full bg-transparent text-sm font-medium text-slate-700 focus:outline-none">
                    <option>Semua Kecamatan</option>
                </select>
            </label>
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Search</span>
                <input type="text" placeholder="Nama sekolah"
                    class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
            </label>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-black/10 px-6 py-4 text-xs text-slate-500">
                <span class="font-semibold uppercase tracking-[0.2em]">Jumlah data: 1</span>
                <label class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tampilkan</span>
                    <select
                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </label>
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
                        <tr class="border-b border-black/5">
                            <td class="px-6 py-4 font-semibold text-slate-900">SD NEGERI BINTARA V</td>
                            <td class="px-6 py-4">20223706</td>
                            <td class="px-6 py-4">Jl. Bintara VIII No.1</td>
                            <td class="px-6 py-4">Bintara</td>
                            <td class="px-6 py-4">Bekasi Barat</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('schools.show', 1) }}"
                                    class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-400" colspan="6">
                                Data lainnya akan muncul setelah integrasi database.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                class="flex flex-col gap-3 border-t border-black/10 px-6 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <span>Menampilkan 1 dari 1 data</span>
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm">
                        Previous
                    </button>
                    <span
                        class="rounded-full border border-black/10 bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">
                        1
                    </span>
                    <span class="px-1 text-xs text-slate-400">...</span>
                    <button type="button"
                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm">
                        7
                    </button>
                    <button type="button"
                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
