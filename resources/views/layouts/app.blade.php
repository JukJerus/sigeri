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
            <header class="sticky top-0 z-40 border-b border-black/10 bg-white/70 backdrop-blur">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-6">
                        <span class="text-xl font-semibold tracking-tight">SIGERI</span>
                        <nav class="hidden items-center gap-2 lg:flex">
                            @foreach ($navItems as $item)
                                @php $isActive = $active === $item['key']; @endphp
                                <a href="{{ $item['href'] }}"
                                    class="rounded-full px-4 py-2 text-sm font-medium transition {{ $isActive ? 'bg-white text-slate-900 shadow-sm ring-1 ring-black/10' : 'text-slate-600 hover:text-slate-900' }}"
                                    @if ($isActive) aria-current="page" @endif>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                    <div class="flex items-center gap-3">
                        @auth
                            <span class="hidden text-sm text-slate-600 sm:inline">{{ Auth::user()->username }}</span>
                            <form action="{{ route('logout') }}" method="POST">
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
                    </div>

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
