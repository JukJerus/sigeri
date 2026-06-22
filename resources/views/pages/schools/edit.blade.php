@extends('layouts.app')

@section('title', 'Edit Sekolah - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Kelola Sekolah</p>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">Edit Data Sekolah</h1>
            <p class="text-sm text-slate-600">Perbarui data <strong>{{ $school->nama }}</strong>.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="mx-auto max-w-3xl">
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

                <form action="{{ route('schools.update', $school->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('pages.schools._form', ['school' => $school, 'kecamatans' => $kecamatans])

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
                        <a href="{{ route('schools.show', $school->id) }}"
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
