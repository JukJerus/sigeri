@extends('layouts.app')

@section('title', 'Laporan Kerusakan - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Laporan</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                    Kerusakan Fasilitas Sekolah
                </h1>
                <p class="text-sm text-slate-600">
                    Daftar laporan kerusakan fasilitas yang telah dilaporkan oleh admin dan operator.
                </p>
            </div>
            <a href="{{ route('kerusakan.create') }}"
                class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Buat Laporan
            </a>
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

        {{-- Filters --}}
        <form action="{{ route('kerusakan.index') }}" method="GET" class="mt-8 flex flex-col gap-3 sm:flex-row">
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Cari</span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama sekolah..."
                    class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
            </label>
            <label
                class="flex items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 whitespace-nowrap">Kondisi</span>
                <select name="kondisi" onchange="this.form.submit()"
                    class="bg-transparent text-sm font-medium text-slate-700 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach (['Ringan', 'Sedang', 'Berat'] as $k)
                        <option value="{{ $k }}" {{ ($kondisi ?? '') === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="hidden">Cari</button>
        </form>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-black/10 px-6 py-4 text-xs text-slate-500">
                <span class="font-semibold uppercase tracking-[0.2em]">Total Laporan: {{ $kerusakans->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse text-left text-sm">
                    <thead class="border-b border-black/10 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Sekolah</th>
                            <th class="px-6 py-4">Foto</th>
                            <th class="px-6 py-4">Jenis Fasilitas</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4">Kondisi</th>
                            <th class="px-6 py-4">Pelapor</th>
                            <th class="px-6 py-4">Tanggal</th>
                            @if (Auth::user()->isAdmin())
                                <th class="px-6 py-4">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse($kerusakans as $item)
                            <tr class="border-b border-black/5 hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $item->sekolah->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->fotos->count() > 0)
                                        <div class="flex items-center gap-1">
                                            <a href="{{ asset('storage/' . $item->fotos->first()->file_foto) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $item->fotos->first()->file_foto) }}"
                                                    alt="Foto kerusakan"
                                                    class="h-10 w-14 rounded-lg object-cover border border-black/10 hover:opacity-80 transition" />
                                            </a>
                                            @if ($item->fotos->count() > 1)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">
                                                    +{{ $item->fotos->count() - 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $item->jenis }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $item->jumlah_kerusakan }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'Ringan' => 'bg-sky-100 text-sky-700',
                                            'Sedang' => 'bg-amber-100 text-amber-700',
                                            'Berat'  => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $colors[$item->kondisi] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $item->kondisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->user->username ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                                @if (Auth::user()->isAdmin())
                                    <td class="px-6 py-4">
                                        <form action="{{ route('kerusakan.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-slate-400" colspan="7">
                                    Belum ada laporan kerusakan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-black/10 px-6 py-4">
                {{ $kerusakans->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
@endsection
