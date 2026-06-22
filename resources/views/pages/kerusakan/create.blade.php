@extends('layouts.app')

@section('title', 'Buat Laporan Kerusakan - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Laporan Baru</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                Laporkan Kerusakan Fasilitas
            </h1>
            <p class="text-sm text-slate-600">
                Isi form di bawah untuk melaporkan kerusakan fasilitas sekolah.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-3xl border border-black/10 bg-white/80 p-8 shadow-sm backdrop-blur animate-rise-in">

                {{-- Error alert --}}
                @if ($errors->any())
                    <div
                        class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold">Terdapat kesalahan:</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kerusakan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Sekolah --}}
                    <div>
                        <label for="sekolah_id"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Sekolah <span class="text-red-400">*</span>
                        </label>
                        <select id="sekolah_id" name="sekolah_id" required
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($sekolahs as $s)
                                <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }} ({{ $s->npsn }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenis Fasilitas --}}
                    <div>
                        <label for="jenis"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Jenis Fasilitas <span class="text-red-400">*</span>
                        </label>
                        <select id="jenis" name="jenis" required
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="">-- Pilih Jenis Fasilitas --</option>
                            @foreach ($jenisOpts as $j)
                                <option value="{{ $j }}" {{ old('jenis') === $j ? 'selected' : '' }}>
                                    {{ $j }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        {{-- Jumlah Kerusakan --}}
                        <div>
                            <label for="jumlah_kerusakan"
                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Jumlah Kerusakan <span class="text-red-400">*</span>
                            </label>
                            <input id="jumlah_kerusakan" name="jumlah_kerusakan" type="number" min="1"
                                value="{{ old('jumlah_kerusakan', 1) }}" required
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                        </div>

                        {{-- Kondisi --}}
                        <div>
                            <label for="kondisi"
                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                Kondisi Kerusakan <span class="text-red-400">*</span>
                            </label>
                            <select id="kondisi" name="kondisi" required
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach ($kondisiOpts as $k)
                                    <option value="{{ $k }}" {{ old('kondisi') === $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="deskripsi"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Deskripsi <span class="text-slate-400">(opsional)</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan detail kerusakan..."
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Foto Dokumentasi --}}
                    <div>
                        <label for="foto"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Foto Dokumentasi <span class="text-slate-400">(opsional, maks. 5 foto)</span>
                        </label>
                        <div class="relative">
                            <input id="foto" name="foto[]" type="file" accept="image/jpeg,image/png,image/webp"
                                multiple
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 transition focus:outline-none" />
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG, WEBP. Maks. 3MB per foto.</p>
                    </div>

                    {{-- Preview Foto --}}
                    <div id="foto-preview-container" class="hidden">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Preview <span id="foto-count" class="text-slate-400"></span>
                        </p>
                        <div id="foto-preview-grid"
                            class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a href="{{ route('kerusakan.index') }}"
                            class="rounded-xl border border-black/10 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const input = document.getElementById('foto');
        const container = document.getElementById('foto-preview-container');
        const grid = document.getElementById('foto-preview-grid');
        const countEl = document.getElementById('foto-count');

        input.addEventListener('change', function () {
            grid.innerHTML = '';
            const files = this.files;

            if (files.length === 0) {
                container.classList.add('hidden');
                return;
            }

            if (files.length > 5) {
                alert('Maksimal 5 foto per laporan.');
                this.value = '';
                container.classList.add('hidden');
                return;
            }

            countEl.textContent = '(' + files.length + ' foto)';
            container.classList.remove('hidden');

            Array.from(files).forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const div = document.createElement('div');
                    div.className = 'relative group overflow-hidden rounded-xl border border-black/10';
                    div.innerHTML = `
                        <img src="${ev.target.result}" alt="Preview ${i + 1}"
                            class="h-32 w-full object-cover" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-xs font-semibold text-white">${file.name.substring(0, 20)}</span>
                        </div>
                    `;
                    grid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush

