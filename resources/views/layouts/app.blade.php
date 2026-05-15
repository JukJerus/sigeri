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
            ['key' => 'lapor', 'label' => 'Lapor Kerusakan', 'href' => '#'],
        ];
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
                        <button type="button"
                            class="hidden rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20 hover:text-slate-900 sm:inline-flex">
                            Masuk
                        </button>
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-black/10 bg-white shadow-sm transition hover:border-black/20"
                            aria-label="Profil">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 20.118a7.5 7.5 0 0 1 15 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.5-1.632Z" />
                            </svg>
                        </button>
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
