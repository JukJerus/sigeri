<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Form upload foto untuk sekolah tertentu.
     */
    public function create($sekolahId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->canAccessSekolah($sekolahId)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $school = Sekolah::findOrFail($sekolahId);

        return view('pages.galeri.create', [
            'active' => 'daftar',
            'school' => $school,
        ]);
    }

    /**
     * Simpan foto.
     */
    public function store(Request $request, $sekolahId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->canAccessSekolah($sekolahId)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $request->validate([
            'tipe'    => 'required|in:sekolah,fasilitas',
            'caption' => 'nullable|string|max:255',
            'foto'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto.required' => 'Pilih file foto terlebih dahulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.mimes'    => 'Format yang diizinkan: JPG, PNG, WEBP.',
            'foto.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        $school = Sekolah::findOrFail($sekolahId);

        // Simpan file ke storage/app/public/galeri/{sekolah_id}/
        $path = $request->file('foto')->store("galeri/{$sekolahId}", 'public');

        Galeri::create([
            'sekolah_id' => $school->id,
            'tipe'       => $request->tipe,
            'file_foto'  => $path,
            'caption'    => $request->caption,
        ]);

        return redirect()->route('schools.show', $school->id)
            ->with('success', 'Foto berhasil diupload.');
    }

    /**
     * Hapus foto (admin & operator pemilik sekolah).
     */
    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);
        /** @var \App\Models\User $user */
        $user   = Auth::user();

        if (! $user->canAccessSekolah($galeri->sekolah_id)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Hapus file dari storage
        if (Storage::disk('public')->exists($galeri->file_foto)) {
            Storage::disk('public')->delete($galeri->file_foto);
        }

        $sekolahId = $galeri->sekolah_id;
        $galeri->delete();

        return redirect()->route('schools.show', $sekolahId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
