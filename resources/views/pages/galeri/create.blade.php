@extends('layouts.app')

@section('title', 'Upload Foto - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Upload Foto</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                {{ $school->nama }}
            </h1>
            <p class="text-sm text-slate-600">
                Upload foto sekolah atau fasilitas untuk ditampilkan di halaman detail.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-3xl border border-black/10 bg-white/80 p-8 shadow-sm backdrop-blur animate-rise-in">

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('galeri.store', $school->id) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    {{-- Tipe Foto --}}
                    <div>
                        <label for="tipe"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Tipe Foto <span class="text-red-400">*</span>
                        </label>
                        <select id="tipe" name="tipe" required
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            <option value="sekolah" {{ old('tipe') === 'sekolah' ? 'selected' : '' }}>Foto Sekolah</option>
                            <option value="fasilitas" {{ old('tipe') === 'fasilitas' ? 'selected' : '' }}>Foto Fasilitas
                            </option>
                        </select>
                    </div>

                    {{-- File --}}
                    <div>
                        <label for="foto"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Pilih Foto <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" required
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 transition focus:outline-none" />
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Format: JPG, PNG, WEBP. Maks. 2MB.</p>
                    </div>

                    {{-- Caption --}}
                    <div>
                        <label for="caption"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Caption <span class="text-slate-400">(opsional)</span>
                        </label>
                        <input id="caption" name="caption" type="text" value="{{ old('caption') }}"
                            placeholder="Contoh: Ruang kelas lantai 2"
                            class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                    </div>

                    {{-- Preview --}}
                    <div id="preview-container" class="hidden">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Preview</p>
                        <div class="overflow-hidden rounded-2xl border border-black/10">
                            <img id="preview-img" class="w-full object-cover" style="max-height: 300px;" alt="Preview" />
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a href="{{ route('schools.show', $school->id) }}"
                            class="rounded-xl border border-black/10 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:border-black/20">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                            Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = document.getElementById('preview-container');
            const img = document.getElementById('preview-img');
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    img.src = ev.target.result;
                    container.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                container.classList.add('hidden');
            }
        });
    </script>
@endpush
