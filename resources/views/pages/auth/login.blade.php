@extends('layouts.app')

@section('title', 'Portal Admin - SIGERI')

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-160px)] max-w-6xl items-center justify-center px-6 py-16">
        <div class="w-full max-w-md animate-rise-in">

            {{-- Header --}}
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl border border-black/10 bg-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-7 w-7 text-slate-700" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Portal Admin</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Halaman ini khusus untuk Admin Dinas Pendidikan dan Operator Sekolah.
                </p>
            </div>

            {{-- Card --}}
            <div class="rounded-3xl border border-black/10 bg-white/80 p-8 shadow-sm backdrop-blur">

                {{-- Alert error --}}
                @if ($errors->any())
                    <div
                        class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <svg viewBox="0 0 20 20" class="mt-0.5 h-5 w-5 shrink-0 fill-red-500">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Username / Email --}}
                    <div>
                        <label for="login"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Username atau Email
                        </label>
                        <input id="login" name="login" type="text" value="{{ old('login') }}" required
                            autocomplete="username" autofocus placeholder="Masukkan username atau email"
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Password
                        </label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-black/20 text-slate-900 focus:ring-slate-300"
                            {{ old('remember') ? 'checked' : '' }} />
                        <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                        Masuk
                    </button>
                </form>
            </div>

            {{-- Footer note --}}
            <p class="mt-6 text-center text-xs text-slate-400">
                Hubungi Dinas Pendidikan jika Anda lupa kredensial akun.
            </p>
        </div>
    </section>
@endsection
