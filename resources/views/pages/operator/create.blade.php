@extends('layouts.app')

@section('title', 'Tambah Operator - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Manajemen Operator</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">Tambah Operator Baru</h1>
            <p class="text-sm text-slate-600">Buat akun operator beserta penugasan sekolah.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-3xl border border-black/10 bg-white/80 p-8 shadow-sm backdrop-blur animate-rise-in">

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold">Terdapat kesalahan:</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('operator.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Informasi Operator --}}
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Informasi Operator</h3>
                        <p class="text-xs text-slate-500">Data profil operator sekolah.</p>
                    </div>

                    <div>
                        <label for="nama" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input id="nama" name="nama" type="text" value="{{ old('nama') }}" required
                            placeholder="Nama lengkap operator"
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="telepon" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                No. Telepon
                            </label>
                            <input id="telepon" name="telepon" type="text" value="{{ old('telepon') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="alamat" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Alamat
                            </label>
                            <input id="alamat" name="alamat" type="text" value="{{ old('alamat') }}"
                                placeholder="Alamat operator"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <hr class="border-black/10" />

                    {{-- Akun Login --}}
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Akun Login</h3>
                        <p class="text-xs text-slate-500">Kredensial yang akan digunakan operator untuk masuk ke sistem.</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="username" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                placeholder="username_operator"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                placeholder="operator@email.com"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input id="password" name="password" type="password" required placeholder="Minimal 6 karakter"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Konfirmasi Password <span class="text-red-400">*</span>
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                placeholder="Ulangi password"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <hr class="border-black/10" />

                    {{-- Penugasan Sekolah --}}
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Penugasan Sekolah</h3>
                        <p class="text-xs text-slate-500">Pilih sekolah yang akan dikelola oleh operator ini. Hanya sekolah
                            yang belum memiliki operator yang ditampilkan.</p>
                    </div>

                    <div class="max-h-64 overflow-y-auto rounded-xl border border-black/10 bg-white">
                        @forelse($sekolahs as $s)
                            <label
                                class="flex cursor-pointer items-center gap-3 border-b border-black/5 px-4 py-3 transition hover:bg-slate-50 last:border-b-0">
                                <input type="checkbox" name="sekolah_ids[]" value="{{ $s->id }}"
                                    {{ in_array($s->id, old('sekolah_ids', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-black/20 text-slate-900 focus:ring-slate-300" />
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $s->nama }}</p>
                                    <p class="text-xs text-slate-500">NPSN: {{ $s->npsn }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-slate-400">
                                Semua sekolah sudah memiliki operator.
                            </div>
                        @endforelse
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a href="{{ route('operator.index') }}"
                            class="rounded-xl border border-black/10 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                            Simpan Operator
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
