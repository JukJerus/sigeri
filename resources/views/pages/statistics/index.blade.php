@extends('layouts.app')

@section('title', 'Statistik - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Statistik</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                Statistik SD Negeri Kota Bekasi
            </h1>
            <p class="text-sm text-slate-600">
                Data statistik berdasarkan informasi dari {{ $totalSekolah }} sekolah yang terdaftar.
            </p>
        </div>

        {{-- Summary Cards --}}
        <div class="mt-8 grid gap-4 sm:grid-cols-3 lg:grid-cols-6">
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Sekolah</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalSekolah) }}</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kecamatan</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalKecamatan }}</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kelurahan</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalKelurahan }}</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Siswa</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalSiswaL + $totalSiswaP) }}</p>
                <p class="mt-1 text-xs text-slate-500">L: {{ number_format($totalSiswaL) }} · P: {{ number_format($totalSiswaP) }}</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Guru</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalGuru) }}</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Tendik</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalTendik) }}</p>
            </div>
        </div>
    </section>

    {{-- Charts --}}
    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Bar Chart: Sekolah per Kecamatan --}}
            <div class="lg:col-span-2 rounded-3xl border border-black/10 bg-white/80 p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Sekolah per Kecamatan</h2>
                        <p class="text-sm text-slate-600">Distribusi jumlah SD Negeri di setiap kecamatan.</p>
                    </div>
                </div>
                <div class="mt-6 overflow-x-auto">
                    <div class="min-w-[600px]">
                        <div class="relative h-[320px]">
                            <canvas id="kecamatan-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doughnut Chart: Akreditasi --}}
            <div class="rounded-3xl border border-black/10 bg-white/80 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Distribusi Akreditasi</h2>
                <p class="text-sm text-slate-600">Persentase sekolah berdasarkan akreditasi.</p>
                <div class="mx-auto mt-6 flex items-center justify-center" style="max-width: 260px; max-height: 260px;">
                    <canvas id="akreditasi-chart"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap justify-center gap-3">
                    @foreach ($akreditasi as $a)
                        @php
                            $colors = [
                                'A' => 'bg-emerald-500',
                                'B' => 'bg-sky-500',
                                'C' => 'bg-amber-500',
                                'Belum' => 'bg-slate-300',
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full {{ $colors[$a->label] ?? 'bg-slate-300' }}"></span>
                            {{ $a->label }} ({{ $a->total }})
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Siswa L/P Bar Chart + Fasilitas Rata-rata --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">

            {{-- Siswa per Kecamatan --}}
            <div class="rounded-3xl border border-black/10 bg-white/80 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Komposisi Siswa</h2>
                <p class="text-sm text-slate-600">Perbandingan siswa laki-laki dan perempuan.</p>
                <div class="mt-6 relative h-[260px]">
                    <canvas id="siswa-chart"></canvas>
                </div>
            </div>

            {{-- Rata-rata Fasilitas --}}
            <div class="rounded-3xl border border-black/10 bg-white/80 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Rata-rata Fasilitas per Sekolah</h2>
                <p class="text-sm text-slate-600">Jumlah rata-rata fasilitas yang dimiliki tiap sekolah.</p>
                <div class="mt-6 space-y-5">
                    @php
                        $fasilitasList = [
                            ['Ruang Kelas', $fasilitasAvg->avg_kelas ?? 0, 'bg-slate-900'],
                            ['Perpustakaan', $fasilitasAvg->avg_perpus ?? 0, 'bg-emerald-500'],
                            ['Lab Komputer', $fasilitasAvg->avg_lab_komputer ?? 0, 'bg-sky-500'],
                            ['Lab IPA', $fasilitasAvg->avg_lab_ipa ?? 0, 'bg-amber-500'],
                        ];
                        $maxVal = max(array_column($fasilitasList, 1)) ?: 1;
                    @endphp
                    @foreach ($fasilitasList as [$label, $val, $color])
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $label }}</span>
                                <span class="font-semibold text-slate-900">{{ $val }}</span>
                            </div>
                            <div class="mt-2 h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="{{ $color }} h-full rounded-full transition-all duration-700"
                                    style="width: {{ ($val / $maxVal) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabel Rincian per Kecamatan --}}
        <div class="mt-6 rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Rincian per Kecamatan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[500px] border-collapse text-left text-sm">
                    <thead class="border-b border-black/10 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kecamatan</th>
                            <th class="px-6 py-4 text-right">Jumlah Sekolah</th>
                            <th class="px-6 py-4 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @foreach ($sekolahPerKecamatan as $i => $item)
                            <tr class="border-b border-black/5 hover:bg-slate-50">
                                <td class="px-6 py-3 text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 font-semibold text-slate-900">{{ $item->nama }}</td>
                                <td class="px-6 py-3 text-right font-semibold">{{ $item->total }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="h-2 w-16 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-slate-900"
                                                style="width: {{ $totalSekolah > 0 ? round(($item->total / $totalSekolah) * 100, 1) : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs text-slate-500">
                                            {{ $totalSekolah > 0 ? round(($item->total / $totalSekolah) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-black/10 font-semibold text-slate-900">
                        <tr>
                            <td class="px-6 py-3" colspan="2">Total</td>
                            <td class="px-6 py-3 text-right">{{ $totalSekolah }}</td>
                            <td class="px-6 py-3 text-right text-xs text-slate-500">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const kecLabels = @json($sekolahPerKecamatan->pluck('nama'));
            const kecValues = @json($sekolahPerKecamatan->pluck('total'));

            // ── Bar Chart: Sekolah per Kecamatan ──
            new Chart(document.getElementById('kecamatan-chart'), {
                type: 'bar',
                data: {
                    labels: kecLabels,
                    datasets: [{
                        label: 'Jumlah SD Negeri',
                        data: kecValues,
                        backgroundColor: 'rgba(15, 23, 42, 0.85)',
                        borderRadius: 999,
                        barThickness: 22,
                        maxBarThickness: 28,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#64748b' },
                            grid: { color: 'rgba(15, 23, 42, 0.06)' },
                        },
                        x: {
                            ticks: { color: '#64748b', maxRotation: 50, minRotation: 50 },
                            grid: { display: false },
                        },
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (c) => `Jumlah: ${c.raw}` } },
                    },
                },
            });

            // ── Doughnut Chart: Akreditasi ──
            const akrLabels = @json($akreditasi->pluck('label'));
            const akrValues = @json($akreditasi->pluck('total'));
            const akrColors = akrLabels.map(l => ({
                'A': '#10b981', 'B': '#0ea5e9', 'C': '#f59e0b', 'Belum': '#cbd5e1'
            })[l] || '#cbd5e1');

            new Chart(document.getElementById('akreditasi-chart'), {
                type: 'doughnut',
                data: {
                    labels: akrLabels,
                    datasets: [{
                        data: akrValues,
                        backgroundColor: akrColors,
                        borderWidth: 2,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (c) => {
                                    const total = c.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = ((c.raw / total) * 100).toFixed(1);
                                    return `${c.label}: ${c.raw} (${pct}%)`;
                                }
                            }
                        },
                    },
                },
            });

            // ── Pie Chart: Siswa L/P ──
            new Chart(document.getElementById('siswa-chart'), {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [{{ $totalSiswaL }}, {{ $totalSiswaP }}],
                        backgroundColor: ['#0f172a', '#94a3b8'],
                        borderWidth: 2,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', color: '#334155' },
                        },
                        tooltip: {
                            callbacks: {
                                label: (c) => {
                                    const total = c.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = ((c.raw / total) * 100).toFixed(1);
                                    return `${c.label}: ${c.raw.toLocaleString()} (${pct}%)`;
                                }
                            }
                        },
                    },
                },
            });
        });
    </script>
@endpush
