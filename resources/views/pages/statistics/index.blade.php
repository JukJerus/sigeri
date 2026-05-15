@extends('layouts.app')

@section('title', 'Statistik - SIGERI')

@section('content')
    @php
        $stats = [
            ['label' => 'Medan Satria', 'value' => 14],
            ['label' => 'Rawalumbu', 'value' => 28],
            ['label' => 'Pondok Gede', 'value' => 19],
            ['label' => 'Jatiasih', 'value' => 16],
            ['label' => 'Bantargebang', 'value' => 30],
            ['label' => 'Bekasi Barat', 'value' => 23],
            ['label' => 'Bekasi Selatan', 'value' => 27],
            ['label' => 'Bekasi Utara', 'value' => 11],
            ['label' => 'Bekasi Timur', 'value' => 18],
            ['label' => 'Jatisampurna', 'value' => 20],
            ['label' => 'Mustikajaya', 'value' => 25],
            ['label' => 'Pondok Melati', 'value' => 29],
        ];
        $labels = array_column($stats, 'label');
        $values = array_column($stats, 'value');
    @endphp

    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Statistik</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                Statistik SD Negeri Kota Bekasi
            </h1>
            <p class="text-sm text-slate-600">
                Jumlah SD Negeri yang ada di tiap kecamatan.
            </p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total SD Negeri</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">320</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kecamatan</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">12</p>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 shadow-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kelurahan</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">56</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Grafik per kecamatan</h2>
                    <p class="text-sm text-slate-600">Distribusi jumlah sekolah per wilayah kecamatan.</p>
                </div>
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Dummy data</span>
            </div>

            <div class="mt-6 overflow-x-auto">
                <div class="min-w-[760px]">
                    <div class="relative h-[320px]">
                        <canvas id="schools-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('schools-chart');
            if (!canvas) {
                return;
            }

            const labels = @json($labels);
            const values = @json($values);

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Jumlah SD Negeri',
                        data: values,
                        backgroundColor: 'rgba(15, 23, 42, 0.85)',
                        borderRadius: 999,
                        barThickness: 22,
                        maxBarThickness: 28,
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#64748b',
                            },
                            grid: {
                                color: 'rgba(15, 23, 42, 0.08)',
                            },
                        },
                        x: {
                            ticks: {
                                color: '#64748b',
                                maxRotation: 50,
                                minRotation: 50,
                            },
                            grid: {
                                display: false,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Jumlah: ${context.raw}`,
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush
