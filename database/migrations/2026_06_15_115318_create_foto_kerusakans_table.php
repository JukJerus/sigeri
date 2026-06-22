<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_kerusakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerusakan_id')->constrained('kerusakans')->onDelete('cascade');
            $table->string('file_foto');
            $table->timestamps();
        });

        // Hapus kolom foto lama dari kerusakans
        Schema::table('kerusakans', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_kerusakans');

        Schema::table('kerusakans', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('deskripsi');
        });
    }
};
