@extends('layouts.app')

@section('title', 'Edit Operator - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Manajemen Operator</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">Edit Operator</h1>
            <p class="text-sm text-slate-600">Perbarui data operator <strong>{{ $operator->nama }}</strong>.</p>
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

                <form action="{{ route('operator.update', $operator->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Informasi Operator --}}
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Informasi Operator</h3>
                    </div>

                    <div>
                        <label for="nama" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input id="nama" name="nama" type="text"
                            value="{{ old('nama', $operator->nama) }}" required
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="telepon" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                No. Telepon
                            </label>
                            <input id="telepon" name="telepon" type="text"
                                value="{{ old('telepon', $operator->telepon) }}"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="alamat" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Alamat
                            </label>
                            <input id="alamat" name="alamat" type="text"
                                value="{{ old('alamat', $operator->alamat) }}"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <hr class="border-black/10" />

                    {{-- Akun Login --}}
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Akun Login</h3>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="username" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <input id="username" name="username" type="text"
                                value="{{ old('username', $operator->user->username) }}" required
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input id="email" name="email" type="email"
                                value="{{ old('email', $operator->user->email) }}" required
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Password Baru
                            </label>
                            <input id="password" name="password" type="password"
                                placeholder="Kosongkan jika tidak diubah"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Konfirmasi Password
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="Ulangi password baru"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-xs text-sky-700">
                        <svg viewBox="0 0 20 20" class="mt-0.5 h-4 w-4 shrink-0 fill-sky-500">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                                clip-rule="evenodd" />
                        </svg>
                        Kosongkan field password jika tidak ingin mengubah password operator.
                    </div>

                    <hr class="border-black/10" />

                    {{-- Penugasan Sekolah --}}
                    @php
                        $assignedIds = $operator->sekolah->pluck('id')->toArray();
                    @endphp

                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-slate-900">Penugasan Sekolah</h3>
                        <p class="text-xs text-slate-500">Centang sekolah yang dikelola operator ini.</p>
                    </div>

                    <div class="max-h-64 overflow-y-auto rounded-xl border border-black/10 bg-white">
                        @forelse($sekolahs as $s)
                            <label
                                class="flex cursor-pointer items-center gap-3 border-b border-black/5 px-4 py-3 transition hover:bg-slate-50 last:border-b-0">
                                <input type="checkbox" name="sekolah_ids[]" value="{{ $s->id }}"
                                    {{ in_array($s->id, old('sekolah_ids', $assignedIds)) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-black/20 text-slate-900 focus:ring-slate-300" />
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $s->nama }}</p>
                                    <p class="text-xs text-slate-500">NPSN: {{ $s->npsn }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-slate-400">
                                Tidak ada sekolah tersedia.
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
