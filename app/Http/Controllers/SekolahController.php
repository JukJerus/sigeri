<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
{
    /**
     * Form tambah sekolah (admin only).
     */
    public function create()
    {
        $kecamatans = Kecamatan::with('kelurahan')->orderBy('nama')->get();

        return view('pages.schools.create', [
            'active'     => 'daftar',
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Simpan sekolah baru (admin only).
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules(), $this->validationMessages());

        DB::transaction(function () use ($request) {
            $sekolah = Sekolah::create([
                'nama'                   => $request->nama,
                'npsn'                   => $request->npsn,
                'alamat'                 => $request->alamat,
                'kelurahan_id'           => $request->kelurahan_id,
                'latitude'               => $request->latitude,
                'longitude'              => $request->longitude,
                'akreditasi'             => $request->akreditasi,
                'jumlah_rombel'          => $request->jumlah_rombel,
                'jumlah_siswa_laki'      => $request->jumlah_siswa_laki,
                'jumlah_siswa_perempuan' => $request->jumlah_siswa_perempuan,
                'jumlah_guru'            => $request->jumlah_guru,
                'jumlah_tendik'          => $request->jumlah_tendik,
            ]);

            Fasilitas::create([
                'sekolah_id'           => $sekolah->id,
                'jumlah_kelas'         => $request->jumlah_kelas,
                'jumlah_perpustakaan'  => $request->jumlah_perpustakaan,
                'jumlah_lab_komputer'  => $request->jumlah_lab_komputer,
                'jumlah_lab_ipa'       => $request->jumlah_lab_ipa,
                'jumlah_ruang_kepsek'  => $request->jumlah_ruang_kepsek,
                'jumlah_ruang_guru'    => $request->jumlah_ruang_guru,
                'jumlah_ruang_tu'      => $request->jumlah_ruang_tu,
                'jumlah_wcg_laki'      => $request->jumlah_wcg_laki,
                'jumlah_wcg_perempuan' => $request->jumlah_wcg_perempuan,
                'jumlah_wcs_laki'      => $request->jumlah_wcs_laki,
                'jumlah_wcs_perempuan' => $request->jumlah_wcs_perempuan,
            ]);
        });

        return redirect()->route('schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Form edit sekolah (admin: semua, operator: hanya sekolahnya).
     */
    public function edit($id)
    {
        $user   = Auth::user();
        $school = Sekolah::with('fasilitas')->findOrFail($id);

        if (! $user->canAccessSekolah($id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $kecamatans = Kecamatan::with('kelurahan')->orderBy('nama')->get();

        return view('pages.schools.edit', [
            'active'     => 'daftar',
            'school'     => $school,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Update data sekolah.
     */
    public function update(Request $request, $id)
    {
        $user   = Auth::user();
        $school = Sekolah::with('fasilitas')->findOrFail($id);

        if (! $user->canAccessSekolah($id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $rules = $this->validationRules($id);
        $request->validate($rules, $this->validationMessages());

        DB::transaction(function () use ($request, $school) {
            $school->update([
                'nama'                   => $request->nama,
                'npsn'                   => $request->npsn,
                'alamat'                 => $request->alamat,
                'kelurahan_id'           => $request->kelurahan_id,
                'latitude'               => $request->latitude,
                'longitude'              => $request->longitude,
                'akreditasi'             => $request->akreditasi,
                'jumlah_rombel'          => $request->jumlah_rombel,
                'jumlah_siswa_laki'      => $request->jumlah_siswa_laki,
                'jumlah_siswa_perempuan' => $request->jumlah_siswa_perempuan,
                'jumlah_guru'            => $request->jumlah_guru,
                'jumlah_tendik'          => $request->jumlah_tendik,
            ]);

            $fasilitasData = [
                'jumlah_kelas'         => $request->jumlah_kelas,
                'jumlah_perpustakaan'  => $request->jumlah_perpustakaan,
                'jumlah_lab_komputer'  => $request->jumlah_lab_komputer,
                'jumlah_lab_ipa'       => $request->jumlah_lab_ipa,
                'jumlah_ruang_kepsek'  => $request->jumlah_ruang_kepsek,
                'jumlah_ruang_guru'    => $request->jumlah_ruang_guru,
                'jumlah_ruang_tu'      => $request->jumlah_ruang_tu,
                'jumlah_wcg_laki'      => $request->jumlah_wcg_laki,
                'jumlah_wcg_perempuan' => $request->jumlah_wcg_perempuan,
                'jumlah_wcs_laki'      => $request->jumlah_wcs_laki,
                'jumlah_wcs_perempuan' => $request->jumlah_wcs_perempuan,
            ];

            if ($school->fasilitas) {
                $school->fasilitas->update($fasilitasData);
            } else {
                Fasilitas::create(array_merge(['sekolah_id' => $school->id], $fasilitasData));
            }
        });

        return redirect()->route('schools.show', $school->id)
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    /**
     * Hapus sekolah (admin only).
     */
    public function destroy($id)
    {
        $school = Sekolah::findOrFail($id);
        $school->delete();

        return redirect()->route('schools.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    /**
     * Ekspor data sekolah ke CSV (admin only).
     */
    public function export(Request $request)
    {
        $query = Sekolah::with(['fasilitas', 'kelurahan.kecamatan', 'operator']);

        // Terapkan filter yang sama seperti di halaman index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kecamatan')) {
            $query->whereHas('kelurahan', fn($q) => $q->where('kecamatan_id', $request->kecamatan));
        }

        if ($request->filled('akreditasi')) {
            $query->where('akreditasi', $request->akreditasi);
        }

        $schools = $query->orderBy('nama')->get();

        $headers = [
            'Nama Satuan Pendidikan',
            'NPSN',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Akreditasi',
            'Latitude',
            'Longitude',
            'Jumlah Rombel',
            'Siswa Laki-laki',
            'Siswa Perempuan',
            'Total Siswa',
            'Jumlah Guru',
            'Jumlah Tendik',
            'Ruang Kelas',
            'Perpustakaan',
            'Lab Komputer',
            'Lab IPA',
            'Ruang Kepala Sekolah',
            'Ruang Guru',
            'Ruang TU',
            'WC Guru (L)',
            'WC Guru (P)',
            'WC Siswa (L)',
            'WC Siswa (P)',
            'Nama Operator',
            'Telepon Operator',
        ];

        $callback = function () use ($schools, $headers) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel bisa baca karakter Indonesia
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $headers);

            foreach ($schools as $s) {
                $f = $s->fasilitas;
                $op = $s->operator;

                fputcsv($file, [
                    $s->nama,
                    $s->npsn,
                    $s->alamat,
                    $s->kelurahan->nama ?? '',
                    $s->kelurahan->kecamatan->nama ?? '',
                    $s->akreditasi ?? '',
                    $s->latitude ?? '',
                    $s->longitude ?? '',
                    $s->jumlah_rombel ?? '',
                    $s->jumlah_siswa_laki ?? '',
                    $s->jumlah_siswa_perempuan ?? '',
                    ($s->jumlah_siswa_laki ?? 0) + ($s->jumlah_siswa_perempuan ?? 0),
                    $s->jumlah_guru ?? '',
                    $s->jumlah_tendik ?? '',
                    $f->jumlah_kelas ?? '',
                    $f->jumlah_perpustakaan ?? '',
                    $f->jumlah_lab_komputer ?? '',
                    $f->jumlah_lab_ipa ?? '',
                    $f->jumlah_ruang_kepsek ?? '',
                    $f->jumlah_ruang_guru ?? '',
                    $f->jumlah_ruang_tu ?? '',
                    $f->jumlah_wcg_laki ?? '',
                    $f->jumlah_wcg_perempuan ?? '',
                    $f->jumlah_wcs_laki ?? '',
                    $f->jumlah_wcs_perempuan ?? '',
                    $op->nama ?? '',
                    $op->telepon ?? '',
                ]);
            }

            fclose($file);
        };

        $filename = 'data_sekolah_' . now()->format('Y-m-d') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Validation rules.
     */
    private function validationRules(?int $ignoreId = null): array
    {
        return [
            'nama'                   => 'required|string|max:255',
            'npsn'                   => 'required|string|max:20|unique:sekolahs,npsn' . ($ignoreId ? ',' . $ignoreId : ''),
            'alamat'                 => 'nullable|string|max:500',
            'kelurahan_id'           => 'required|exists:kelurahans,id',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
            'akreditasi'             => 'nullable|in:A,B,C',
            'jumlah_rombel'          => 'nullable|integer|min:0',
            'jumlah_siswa_laki'      => 'nullable|integer|min:0',
            'jumlah_siswa_perempuan' => 'nullable|integer|min:0',
            'jumlah_guru'            => 'nullable|integer|min:0',
            'jumlah_tendik'          => 'nullable|integer|min:0',
            'jumlah_kelas'           => 'nullable|integer|min:0',
            'jumlah_perpustakaan'    => 'nullable|integer|min:0',
            'jumlah_lab_komputer'    => 'nullable|integer|min:0',
            'jumlah_lab_ipa'         => 'nullable|integer|min:0',
            'jumlah_ruang_kepsek'    => 'nullable|integer|min:0',
            'jumlah_ruang_guru'      => 'nullable|integer|min:0',
            'jumlah_ruang_tu'        => 'nullable|integer|min:0',
            'jumlah_wcg_laki'        => 'nullable|integer|min:0',
            'jumlah_wcg_perempuan'   => 'nullable|integer|min:0',
            'jumlah_wcs_laki'        => 'nullable|integer|min:0',
            'jumlah_wcs_perempuan'   => 'nullable|integer|min:0',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'nama.required'         => 'Nama sekolah wajib diisi.',
            'npsn.required'         => 'NPSN wajib diisi.',
            'npsn.unique'           => 'NPSN sudah terdaftar.',
            'kelurahan_id.required' => 'Pilih kelurahan.',
            'kelurahan_id.exists'   => 'Kelurahan tidak valid.',
        ];
    }
}
