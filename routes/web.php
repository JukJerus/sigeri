<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.map', ['active' => 'peta']);
})->name('map');

Route::get('/sekolah', function (\Illuminate\Http\Request $request) {
    $search      = $request->query('search');
    $kecamatanId = $request->query('kecamatan');
    $akreditasi  = $request->query('akreditasi');

    $query = App\Models\Sekolah::with(['kecamatan', 'kelurahan']);

    // Search nama / NPSN
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('npsn', 'like', "%{$search}%");
        });
    }

    // Filter kecamatan
    if ($kecamatanId) {
        $query->whereHas('kelurahan', fn($q) => $q->where('kecamatan_id', $kecamatanId));
    }

    // Filter akreditasi
    if ($akreditasi) {
        $query->where('akreditasi', $akreditasi);
    }

    $schools    = $query->orderBy('nama')->paginate(15)->withQueryString();
    $kecamatans = App\Models\Kecamatan::orderBy('nama')->get();

    return view('pages.schools.index', [
        'active'       => 'daftar',
        'schools'      => $schools,
        'search'       => $search,
        'kecamatans'   => $kecamatans,
        'kecamatanId'  => $kecamatanId,
        'akreditasi'   => $akreditasi,
    ]);
})->name('schools.index');

Route::get('/statistik', function () {
    $totalSekolah   = App\Models\Sekolah::count();
    $totalKecamatan = App\Models\Kecamatan::count();
    $totalKelurahan = App\Models\Kelurahan::count();

    // Jumlah sekolah per kecamatan
    $perKecamatan = App\Models\Kecamatan::withCount(['kelurahan as sekolah_count' => function ($q) {
        $q->join('sekolahs', 'sekolahs.kelurahan_id', '=', 'kelurahans.id')
          ->select(Illuminate\Support\Facades\DB::raw('count(sekolahs.id)'));
    }])->orderBy('nama')->get();

    // Query langsung agar akurat
    $sekolahPerKecamatan = Illuminate\Support\Facades\DB::table('sekolahs')
        ->join('kelurahans', 'sekolahs.kelurahan_id', '=', 'kelurahans.id')
        ->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
        ->select('kecamatans.nama', Illuminate\Support\Facades\DB::raw('count(sekolahs.id) as total'))
        ->groupBy('kecamatans.nama')
        ->orderBy('kecamatans.nama')
        ->get();

    // Distribusi akreditasi
    $akreditasi = App\Models\Sekolah::selectRaw("COALESCE(akreditasi, 'Belum') as label, count(*) as total")
        ->groupBy('akreditasi')
        ->orderByRaw("FIELD(akreditasi, 'A', 'B', 'C') ASC")
        ->get();

    // Total siswa & guru
    $totalSiswaL = App\Models\Sekolah::sum('jumlah_siswa_laki') ?: 0;
    $totalSiswaP = App\Models\Sekolah::sum('jumlah_siswa_perempuan') ?: 0;
    $totalGuru   = App\Models\Sekolah::sum('jumlah_guru') ?: 0;
    $totalTendik = App\Models\Sekolah::sum('jumlah_tendik') ?: 0;

    // Rata-rata fasilitas
    $fasilitasAvg = App\Models\Fasilitas::selectRaw('
        ROUND(AVG(jumlah_kelas), 1) as avg_kelas,
        ROUND(AVG(jumlah_perpustakaan), 1) as avg_perpus,
        ROUND(AVG(jumlah_lab_komputer), 1) as avg_lab_komputer,
        ROUND(AVG(jumlah_lab_ipa), 1) as avg_lab_ipa
    ')->first();

    return view('pages.statistics.index', [
        'active'              => 'statistik',
        'totalSekolah'        => $totalSekolah,
        'totalKecamatan'      => $totalKecamatan,
        'totalKelurahan'      => $totalKelurahan,
        'sekolahPerKecamatan' => $sekolahPerKecamatan,
        'akreditasi'          => $akreditasi,
        'totalSiswaL'         => $totalSiswaL,
        'totalSiswaP'         => $totalSiswaP,
        'totalGuru'           => $totalGuru,
        'totalTendik'         => $totalTendik,
        'fasilitasAvg'        => $fasilitasAvg,
    ]);
})->name('statistics.index');

Route::get('/sekolah/{id}', function ($id) {
    $school = App\Models\Sekolah::with(['fasilitas', 'kecamatan', 'kelurahan', 'operator', 'galeri'])->findOrFail($id);
    return view('pages.schools.show', [
        'active' => 'daftar',
        'school' => $school
    ]);
})->name('schools.show');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// ── Fitur Admin & Operator (perlu login) ────────────────
Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    Route::get('/kerusakan', [App\Http\Controllers\KerusakanController::class, 'index'])->name('kerusakan.index');
    Route::get('/kerusakan/create', [App\Http\Controllers\KerusakanController::class, 'create'])->name('kerusakan.create');
    Route::post('/kerusakan', [App\Http\Controllers\KerusakanController::class, 'store'])->name('kerusakan.store');

    // Galeri foto
    Route::get('/sekolah/{sekolah}/galeri/upload', [App\Http\Controllers\GaleriController::class, 'create'])->name('galeri.create');
    Route::post('/sekolah/{sekolah}/galeri', [App\Http\Controllers\GaleriController::class, 'store'])->name('galeri.store');
    Route::delete('/galeri/{id}', [App\Http\Controllers\GaleriController::class, 'destroy'])->name('galeri.destroy');

    // Edit sekolah (admin: semua, operator: hanya sekolahnya — dicek di controller)
    Route::get('/sekolah/{id}/edit', [App\Http\Controllers\SekolahController::class, 'edit'])->name('schools.edit');
    Route::put('/sekolah/{id}', [App\Http\Controllers\SekolahController::class, 'update'])->name('schools.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/kerusakan/{id}', [App\Http\Controllers\KerusakanController::class, 'destroy'])->name('kerusakan.destroy');

    // Manajemen Operator (hanya admin)
    Route::get('/operator', [App\Http\Controllers\OperatorController::class, 'index'])->name('operator.index');
    Route::get('/operator/create', [App\Http\Controllers\OperatorController::class, 'create'])->name('operator.create');
    Route::post('/operator', [App\Http\Controllers\OperatorController::class, 'store'])->name('operator.store');
    Route::get('/operator/{id}/edit', [App\Http\Controllers\OperatorController::class, 'edit'])->name('operator.edit');
    Route::put('/operator/{id}', [App\Http\Controllers\OperatorController::class, 'update'])->name('operator.update');
    Route::delete('/operator/{id}', [App\Http\Controllers\OperatorController::class, 'destroy'])->name('operator.destroy');

    // Kelola Sekolah - Tambah, Hapus & Ekspor (hanya admin)
    Route::get('/sekolah-tambah', [App\Http\Controllers\SekolahController::class, 'create'])->name('schools.create');
    Route::post('/sekolah', [App\Http\Controllers\SekolahController::class, 'store'])->name('schools.store');
    Route::delete('/sekolah/{id}', [App\Http\Controllers\SekolahController::class, 'destroy'])->name('schools.destroy');
    Route::get('/sekolah-ekspor', [App\Http\Controllers\SekolahController::class, 'export'])->name('schools.export');
});
