@extends('layouts.app')

@section('title', 'Manajemen Operator - SIGERI')

@section('content')
    <section class="mx-auto max-w-6xl px-6 pt-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Admin</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                    Manajemen Operator
                </h1>
                <p class="text-sm text-slate-600">
                    Kelola data operator sekolah dan penugasan mereka ke setiap sekolah.
                </p>
            </div>
            <a href="{{ route('operator.create') }}"
                class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.98]">
                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Tambah Operator
            </a>
        </div>

        {{-- Success alert --}}
        @if (session('success'))
            <div
                class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <svg viewBox="0 0 20 20" class="h-5 w-5 shrink-0 fill-emerald-500">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Search --}}
        <form action="{{ route('operator.index') }}" method="GET" class="mt-8">
            <label
                class="flex w-full items-center gap-2 rounded-full border border-black/10 bg-white/80 px-4 py-2 shadow-sm sm:max-w-md">
                <svg viewBox="0 0 20 20" class="h-4 w-4 shrink-0 text-slate-400" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                        clip-rule="evenodd" />
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama, username, atau telepon..."
                    class="w-full bg-transparent text-sm font-medium text-slate-700 placeholder:text-slate-400 focus:outline-none" />
            </label>
        </form>
    </section>

    <section class="mx-auto max-w-6xl px-6 pb-16 pt-8">
        <div class="rounded-3xl border border-black/10 bg-white/80 shadow-sm">
            <div class="border-b border-black/10 px-6 py-4 text-xs text-slate-500">
                <span class="font-semibold uppercase tracking-[0.2em]">Total Operator:
                    {{ $operators->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse text-left text-sm">
                    <thead class="border-b border-black/10 text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Telepon</th>
                            <th class="px-6 py-4">Sekolah Ditugaskan</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse($operators as $op)
                            <tr class="border-b border-black/5 hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $op->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $op->user->email ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex rounded-full border border-black/10 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        {{ $op->user->username ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $op->telepon ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($op->sekolah->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($op->sekolah as $s)
                                                <span
                                                    class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                    {{ $s->nama }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('operator.edit', $op->id) }}"
                                            class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-black/20">
                                            Edit
                                        </a>
                                        <form action="{{ route('operator.destroy', $op->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus operator {{ $op->nama }}? Akun login-nya juga akan dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-slate-400" colspan="5">
                                    Belum ada operator terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-black/10 px-6 py-4">
                {{ $operators->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
@endsection
