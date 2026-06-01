@extends('layouts.app')

@section('title', 'Detail Sekolah - SIGERI')

@section('content')
    @php
        $school = [
            'name' => 'SD NEGERI BINTARA V',
            'accreditation' => 'A',
            'updated' => 'Mei 2026',
            'address' => 'Jl. Bintara VIII No.1, Kec. Bekasi Barat, Kota Bekasi, Jawa Barat',
            'phone' => '08123456789',
            'npsn' => '20223706',
            'teachers' => 19,
            'staff' => 8,
            'students_male' => 78,
            'students_female' => 65,
            'rombel' => 24,
        ];
        $totalStudents = $school['students_male'] + $school['students_female'];
        $facilityRows = [
            ['label' => 'Jumlah Ruang Kelas', 'value' => 7],
            ['label' => 'Jumlah Ruang Perpustakaan', 'value' => 2],
            ['label' => 'Jumlah Lab Komputer', 'value' => 3],
            ['label' => 'Jumlah Lab IPA', 'value' => 8],
            ['label' => 'Jumlah Ruang Kepala Sekolah', 'value' => 1],
            ['label' => 'Jumlah Ruang Guru', 'value' => 2],
            ['label' => 'Jumlah Ruang TU', 'value' => 1],
            ['label' => 'Jumlah WC Guru Laki-laki', 'value' => 1],
            ['label' => 'Jumlah WC Guru Perempuan', 'value' => 1],
            ['label' => 'Jumlah WC Siswa Laki-laki', 'value' => 3],
            ['label' => 'Jumlah WC Siswa Perempuan', 'value' => 3],
        ];
    @endphp

    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Detail Sekolah</p>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                        {{ $school['name'] }}
                    </h1>
                    <span
                        class="rounded-full border border-black/10 bg-white px-3 py-1 text-xs font-semibold text-slate-700">
                        Akreditasi {{ $school['accreditation'] }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-slate-600">Last updated: {{ $school['updated'] }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                    class="rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                    Edit Profil
                </button>
                <button type="button"
                    class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    Edit Fasilitas
                </button>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-6 pt-8">
        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
                <div class="border-b border-black/10 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Profil Sekolah</h2>
                        <button type="button"
                            class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm">
                            Ubah Foto
                        </button>
                    </div>
                </div>
                <div class="space-y-6 p-6">
                    <div class="relative h-[260px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                        </div>
                        <div class="relative flex h-full items-center justify-center text-center">
                            <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                                <p class="text-sm font-semibold text-slate-700">Foto Sekolah</p>
                                <p class="mt-1 text-xs text-slate-500">Carousel foto akan ditampilkan di sini.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                            <p class="mt-2 text-sm font-medium text-slate-900">{{ $school['address'] }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-500">Nomor Telepon</span>
                                <span class="font-semibold text-slate-900">{{ $school['phone'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-500">NPSN</span>
                                <span class="font-semibold text-slate-900">{{ $school['npsn'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
                <div class="border-b border-black/10 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Ringkasan Data</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Jumlah Guru</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school['teachers'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Tenaga Pendidik</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school['staff'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Siswa Laki-laki</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school['students_male'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Siswa Perempuan</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school['students_female'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Siswa</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalStudents }}</p>
                        </div>
                        <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Rombel</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $school['rombel'] }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end">
                        <button type="button"
                            class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-6">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Lokasi Sekolah</h2>
                    <span class="text-xs text-slate-500">Koordinat belum tersedia</span>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-[240px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                    </div>
                    <div class="relative flex h-full items-center justify-center text-center">
                        <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-700">Peta Lokasi</p>
                            <p class="mt-1 text-xs text-slate-500">Tampilkan peta mini sekolah di sini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Fasilitas Sekolah</h2>
                    <button type="button"
                        class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm">
                        Ubah Foto
                    </button>
                </div>
            </div>
            <div class="grid gap-6 p-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="grid gap-4 rounded-2xl border border-black/10 bg-white/70 px-5 py-4 text-sm text-slate-700">
                    @foreach ($facilityRows as $row)
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">{{ $row['label'] }}</span>
                            <span class="font-semibold text-slate-900">{{ $row['value'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="relative h-[240px] overflow-hidden rounded-2xl border border-black/10 bg-[#f1ebe2]">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#f2dcc6_0%,_#f8efe5_45%,_#fcfbf8_100%)]">
                    </div>
                    <div class="relative flex h-full items-center justify-center text-center">
                        <div class="rounded-2xl border border-black/10 bg-white/85 px-6 py-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-700">Foto Fasilitas</p>
                            <p class="mt-1 text-xs text-slate-500">Carousel foto fasilitas sekolah.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-col gap-3 border-t border-black/10 px-6 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <span>*Fasilitas sekolah yang tercantum telah memenuhi standar pendataan sarana prasarana.</span>
                <button type="button"
                    class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    Edit Data
                </button>
            </div>
        </div>
    </section>
@endsection
