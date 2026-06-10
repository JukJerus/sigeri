<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->onDelete('cascade');
            $table->integer('jumlah_kelas')->nullable();
            $table->integer('jumlah_perpustakaan')->nullable();
            $table->integer('jumlah_lab_komputer')->nullable();
            $table->integer('jumlah_lab_ipa')->nullable();
            $table->integer('jumlah_ruang_kepsek')->nullable();
            $table->integer('jumlah_ruang_guru')->nullable();
            $table->integer('jumlah_ruang_tu')->nullable();
            $table->integer('jumlah_wcg_laki')->nullable();
            $table->integer('jumlah_wcg_perempuan')->nullable();
            $table->integer('jumlah_wcs_laki')->nullable();
            $table->integer('jumlah_wcs_perempuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
