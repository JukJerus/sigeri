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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npsn')->unique();
            $table->text('alamat')->nullable();
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahans')->onDelete('set null');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('akreditasi')->nullable();
            $table->integer('jumlah_rombel')->nullable();
            $table->integer('jumlah_siswa_laki')->nullable();
            $table->integer('jumlah_siswa_perempuan')->nullable();
            $table->integer('jumlah_tendik')->nullable();
            $table->integer('jumlah_guru')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained('operators')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
