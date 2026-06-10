<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.map', ['active' => 'peta']);
})->name('map');

Route::get('/sekolah', function (\Illuminate\Http\Request $request) {
    $search = $request->query('search');

    $query = App\Models\Sekolah::with(['kecamatan', 'kelurahan']);

    if ($search) {
        $query->where('nama', 'like', "%{$search}%")
            ->orWhere('npsn', 'like', "%{$search}%");
    }

    $schools = $query->paginate(15)->withQueryString();

    return view('pages.schools.index', [
        'active' => 'daftar',
        'schools' => $schools,
        'search' => $search
    ]);
})->name('schools.index');

Route::get('/statistik', function () {
    return view('pages.statistics.index', ['active' => 'statistik']);
})->name('statistics.index');

Route::get('/sekolah/{id}', function ($id) {
    $school = App\Models\Sekolah::with(['fasilitas', 'kecamatan', 'kelurahan', 'operator'])->findOrFail($id);
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
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/kerusakan/{id}', [App\Http\Controllers\KerusakanController::class, 'destroy'])->name('kerusakan.destroy');
});
