<?php

namespace App\Http\Controllers;

use App\Models\FotoKerusakan;
use App\Models\Kerusakan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KerusakanController extends Controller
{
    /**
     * Daftar laporan kerusakan.
     * Admin: semua laporan. Operator: hanya sekolah miliknya.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $query = Kerusakan::with(['sekolah', 'user', 'fotos'])->latest();

        // Filter berdasarkan akses sekolah user
        $sekolahIds = $user->getSekolahIds();
        $query->when($sekolahIds !== null, fn($q) => $q->whereIn('sekolah_id', $sekolahIds));

        // Filter by kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search by nama sekolah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('sekolah', fn($q) => $q->where('nama', 'like', "%{$search}%"));
        }

        $kerusakans = $query->paginate(15)->withQueryString();

        return view('pages.kerusakan.index', [
            'active'     => 'kerusakan',
            'kerusakans' => $kerusakans,
            'search'     => $request->search,
            'kondisi'    => $request->kondisi,
        ]);
    }

    /**
     * Form buat laporan kerusakan baru.
     * Dropdown sekolah sudah difilter sesuai hak akses.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $sekolahIds = $user->getSekolahIds();

        $sekolahs = Sekolah::orderBy('nama')
            ->when($sekolahIds !== null, fn($q) => $q->whereIn('id', $sekolahIds))
            ->get();

        return view('pages.kerusakan.create', [
            'active'      => 'kerusakan',
            'sekolahs'    => $sekolahs,
            'jenisOpts'   => Kerusakan::JENIS_OPTIONS,
            'kondisiOpts' => Kerusakan::KONDISI_OPTIONS,
        ]);
    }

    /**
     * Simpan laporan baru.
     * Validasi ganda: Laravel validation + cek akses sekolah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sekolah_id'       => 'required|exists:sekolahs,id',
            'jenis'            => 'required|string|max:255',
            'jumlah_kerusakan' => 'required|integer|min:1',
            'kondisi'          => 'required|in:Ringan,Sedang,Berat',
            'deskripsi'        => 'nullable|string|max:1000',
            'foto'             => 'nullable|array|max:5',
            'foto.*'           => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'sekolah_id.required' => 'Pilih sekolah terlebih dahulu.',
            'jenis.required'      => 'Jenis fasilitas wajib diisi.',
            'jumlah_kerusakan.required' => 'Jumlah kerusakan wajib diisi.',
            'jumlah_kerusakan.min'      => 'Jumlah kerusakan minimal 1.',
            'kondisi.required'    => 'Pilih kondisi kerusakan.',
            'kondisi.in'          => 'Kondisi tidak valid.',
            'foto.max'            => 'Maksimal 5 foto per laporan.',
            'foto.*.image'        => 'Setiap file harus berupa gambar.',
            'foto.*.mimes'        => 'Format yang diizinkan: JPG, PNG, WEBP.',
            'foto.*.max'          => 'Ukuran setiap foto maksimal 3MB.',
        ]);

        // Cek akses: operator tidak bisa lapor sekolah orang lain
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->canAccessSekolah($request->sekolah_id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $kerusakan = Kerusakan::create([
            'sekolah_id'       => $request->sekolah_id,
            'jenis'            => $request->jenis,
            'jumlah_kerusakan' => $request->jumlah_kerusakan,
            'kondisi'          => $request->kondisi,
            'deskripsi'        => $request->deskripsi,
            'user_id'          => $user->id,
        ]);

        // Upload semua foto
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('kerusakan/' . $request->sekolah_id, 'public');
                FotoKerusakan::create([
                    'kerusakan_id' => $kerusakan->id,
                    'file_foto'    => $path,
                ]);
            }
        }

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil ditambahkan.');
    }

    /**
     * Hapus laporan (hanya admin via middleware).
     */
    public function destroy($id)
    {
        $kerusakan = Kerusakan::with('fotos')->findOrFail($id);

        // Hapus semua file foto dari storage
        foreach ($kerusakan->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_foto)) {
                Storage::disk('public')->delete($foto->file_foto);
            }
        }

        $kerusakan->delete(); // fotos dihapus otomatis via cascade

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil dihapus.');
    }

    /**
     * Ekspor data kerusakan ke CSV (admin only).
     */
    public function export(Request $request)
    {
        $query = Kerusakan::with(['sekolah.kelurahan.kecamatan', 'user', 'fotos'])->latest();

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search nama sekolah
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('sekolah', fn($q) => $q->where('nama', 'like', "%{$search}%"));
        }

        $kerusakans = $query->get();

        $headers = [
            'No',
            'Nama Sekolah',
            'NPSN',
            'Kecamatan',
            'Kelurahan',
            'Jenis Fasilitas',
            'Jumlah Kerusakan',
            'Kondisi',
            'Deskripsi',
            'Dilaporkan Oleh',
            'Tanggal Laporan',
            'Jumlah Foto',
        ];

        $callback = function () use ($kerusakans, $headers) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel bisa baca karakter Indonesia
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $headers);

            foreach ($kerusakans as $i => $item) {
                $sekolah = $item->sekolah;
                fputcsv($file, [
                    $i + 1,
                    $sekolah?->nama ?? '-',
                    $sekolah?->npsn ?? '-',
                    $sekolah?->kelurahan?->kecamatan?->nama ?? '-',
                    $sekolah?->kelurahan?->nama ?? '-',
                    $item->jenis,
                    $item->jumlah_kerusakan,
                    $item->kondisi,
                    $item->deskripsi ?? '-',
                    $item->user?->username ?? '-',
                    $item->created_at?->format('d/m/Y H:i') ?? '-',
                    $item->fotos?->count() ?? 0,
                ]);
            }

            fclose($file);
        };

        $filename = 'laporan_kerusakan_' . now()->format('Y-m-d') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
