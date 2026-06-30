<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'SIGERI')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>

<body class="min-h-screen bg-[#f6f1e9] text-slate-900 antialiased">
    @php
        $active = $active ?? 'peta';
        $navItems = [
            ['key' => 'peta', 'label' => 'Peta', 'href' => route('map')],
            ['key' => 'daftar', 'label' => 'Daftar Sekolah', 'href' => route('schools.index')],
            ['key' => 'statistik', 'label' => 'Statistik', 'href' => route('statistics.index')],
        ];

        // Tampilkan menu Lapor Kerusakan hanya untuk admin & operator
        if (Auth::check() && Auth::user()->hasRole('admin', 'operator')) {
            $navItems[] = ['key' => 'kerusakan', 'label' => 'Lapor Kerusakan', 'href' => route('kerusakan.index')];
        }

        // Tampilkan menu "Sekolah Saya" hanya untuk operator
        if (Auth::check() && Auth::user()->isOperator()) {
            $sekolahSaya = App\Models\Sekolah::where('operator_id', Auth::user()->operator?->id)
                ->select('id', 'nama')
                ->get();
        }

        // Tampilkan menu Kelola Operator hanya untuk admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            $navItems[] = ['key' => 'operator', 'label' => 'Kelola Operator', 'href' => route('operator.index')];
        }
    @endphp

    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div
                class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-[#f3d7b5] opacity-70 blur-3xl">
            </div>
            <div class="absolute right-[-8rem] top-24 h-80 w-80 rounded-full bg-[#cfe2dd] opacity-60 blur-3xl"></div>
            <div class="absolute bottom-[-8rem] left-[-6rem] h-80 w-80 rounded-full bg-[#f4c3a0] opacity-40 blur-3xl">
            </div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-black/20 to-transparent">
            </div>
        </div>

        <div class="relative z-10">
            <header class="sticky top-0 z-40 border-b border-black/10 bg-white/70 backdrop-blur" x-data="{ mobileOpen: false }">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-6">
                        <span class="text-xl font-semibold tracking-tight">SIGERI</span>

                        {{-- Desktop Nav --}}
                        <nav class="hidden items-center gap-2 lg:flex">
                            @foreach ($navItems as $item)
                                @php $isActive = $active === $item['key']; @endphp
                                <a href="{{ $item['href'] }}"
                                    class="rounded-full px-4 py-2 text-sm font-medium transition {{ $isActive ? 'bg-white text-slate-900 shadow-sm ring-1 ring-black/10' : 'text-slate-600 hover:text-slate-900' }}"
                                    @if ($isActive) aria-current="page" @endif>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach

                            {{-- Sekolah Saya (khusus operator) — Desktop --}}
                            @if (isset($sekolahSaya) && $sekolahSaya->count() > 0)
                                @if ($sekolahSaya->count() === 1)
                                    <a href="{{ route('schools.show', $sekolahSaya->first()->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                                        Sekolah Saya
                                    </a>
                                @else
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false"
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                                            </svg>
                                            Sekolah Saya
                                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                            class="absolute left-0 top-full mt-2 w-64 rounded-2xl border border-black/10 bg-white py-2 shadow-lg">
                                            @foreach ($sekolahSaya as $sk)
                                                <a href="{{ route('schools.show', $sk->id) }}"
                                                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 truncate">
                                                    {{ $sk->nama }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </nav>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Desktop Auth --}}
                        @auth
                            <span class="hidden text-sm text-slate-600 sm:inline">{{ Auth::user()->username }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="hidden lg:block">
                                @csrf
                                <button type="submit"
                                    class="rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20 hover:text-slate-900 inline-flex items-center gap-2">
                                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                            clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="hidden rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20 hover:text-slate-900 sm:inline-flex items-center gap-2">
                                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Portal Admin
                            </a>
                        @endauth

                        {{-- Hamburger Button (Mobile) --}}
                        <button @click="mobileOpen = !mobileOpen" class="rounded-xl border border-black/10 bg-white p-2 shadow-sm lg:hidden"
                            :aria-expanded="mobileOpen" aria-label="Toggle menu">
                            {{-- Hamburger icon --}}
                            <svg x-show="!mobileOpen" viewBox="0 0 20 20" class="h-5 w-5 text-slate-700" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75Zm0 5A.75.75 0 0 1 2.75 9h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 9.75Zm0 5a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{-- Close icon --}}
                            <svg x-show="mobileOpen" x-cloak viewBox="0 0 20 20" class="h-5 w-5 text-slate-700" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- ── Mobile Menu Panel ────────────────────── --}}
                <div x-show="mobileOpen" x-collapse x-cloak
                    class="border-t border-black/10 bg-white/95 backdrop-blur lg:hidden">
                    <nav class="mx-auto max-w-6xl space-y-1 px-6 py-4">
                        @foreach ($navItems as $item)
                            @php $isActive = $active === $item['key']; @endphp
                            <a href="{{ $item['href'] }}"
                                class="block rounded-xl px-4 py-3 text-sm font-medium transition {{ $isActive ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        {{-- Sekolah Saya (mobile) --}}
                        @if (isset($sekolahSaya) && $sekolahSaya->count() > 0)
                            <div class="border-t border-black/5 pt-2 mt-2">
                                <p class="px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Sekolah Saya</p>
                                @foreach ($sekolahSaya as $sk)
                                    <a href="{{ route('schools.show', $sk->id) }}"
                                        class="block rounded-xl px-4 py-3 text-sm font-medium text-emerald-700 hover:bg-emerald-50 truncate">
                                        ⭐ {{ $sk->nama }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Auth Actions (mobile) --}}
                        <div class="border-t border-black/5 pt-2 mt-2">
                            @auth
                                <div class="flex items-center justify-between px-4 py-2">
                                    <span class="text-sm text-slate-500">Login sebagai <strong class="text-slate-700">{{ Auth::user()->username }}</strong></span>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                        <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                                clip-rule="evenodd" />
                                            <path fill-rule="evenodd"
                                                d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Portal Admin
                                </a>
                            @endauth
                        </div>
                    </nav>
                </div>
            </header>

            <main class="pb-16">
                @yield('content')
            </main>

            <footer class="mx-auto max-w-6xl px-6 pb-8 text-xs text-slate-500">
                <div
                    class="flex flex-col items-start gap-2 border-t border-black/10 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <span>WebGIS SD Negeri Kota Bekasi</span>
                    <span>Prototype UI</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
