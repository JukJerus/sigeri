<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.map', ['active' => 'peta']);
})->name('map');

Route::get('/sekolah', function () {
    return view('pages.schools.index', ['active' => 'daftar']);
})->name('schools.index');

Route::get('/statistik', function () {
    return view('pages.statistics.index', ['active' => 'statistik']);
})->name('statistics.index');
