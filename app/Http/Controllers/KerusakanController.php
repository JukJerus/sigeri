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

        // ⛔ Cek akses: operator tidak bisa lapor sekolah orang lain
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
}
