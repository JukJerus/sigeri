<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KerusakanController extends Controller
{
    /**
     * Daftar laporan kerusakan.
     * Admin: semua laporan. Operator: hanya sekolah miliknya.
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Kerusakan::with(['sekolah', 'user'])->latest();

        // Operator hanya lihat kerusakan sekolah yang dia kelola
        if ($user->isOperator()) {
            $operatorSekolahIds = Sekolah::where('operator_id', $user->operator?->id)
                ->pluck('id');
            $query->whereIn('sekolah_id', $operatorSekolahIds);
        }

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
     */
    public function create()
    {
        $user = Auth::user();

        // Admin bisa pilih semua sekolah, operator hanya sekolahnya
        if ($user->isAdmin()) {
            $sekolahs = Sekolah::orderBy('nama')->get();
        } else {
            $sekolahs = Sekolah::where('operator_id', $user->operator?->id)
                ->orderBy('nama')->get();
        }

        return view('pages.kerusakan.create', [
            'active'    => 'kerusakan',
            'sekolahs'  => $sekolahs,
            'jenisOpts' => Kerusakan::JENIS_OPTIONS,
            'kondisiOpts' => Kerusakan::KONDISI_OPTIONS,
        ]);
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sekolah_id'       => 'required|exists:sekolahs,id',
            'jenis'            => 'required|string|max:255',
            'jumlah_kerusakan' => 'required|integer|min:1',
            'kondisi'          => 'required|in:Ringan,Sedang,Berat',
            'deskripsi'        => 'nullable|string|max:1000',
        ], [
            'sekolah_id.required' => 'Pilih sekolah terlebih dahulu.',
            'jenis.required'      => 'Jenis fasilitas wajib diisi.',
            'jumlah_kerusakan.required' => 'Jumlah kerusakan wajib diisi.',
            'jumlah_kerusakan.min'      => 'Jumlah kerusakan minimal 1.',
            'kondisi.required'    => 'Pilih kondisi kerusakan.',
            'kondisi.in'          => 'Kondisi tidak valid.',
        ]);

        // Validasi akses operator
        $user = Auth::user();
        if ($user->isOperator()) {
            $allowed = Sekolah::where('operator_id', $user->operator?->id)
                ->where('id', $request->sekolah_id)->exists();
            if (! $allowed) {
                abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
            }
        }

        Kerusakan::create([
            'sekolah_id'       => $request->sekolah_id,
            'jenis'            => $request->jenis,
            'jumlah_kerusakan' => $request->jumlah_kerusakan,
            'kondisi'          => $request->kondisi,
            'deskripsi'        => $request->deskripsi,
            'user_id'          => $user->id,
        ]);

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil ditambahkan.');
    }

    /**
     * Hapus laporan (hanya admin).
     */
    public function destroy($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        $kerusakan->delete();

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil dihapus.');
    }
}
