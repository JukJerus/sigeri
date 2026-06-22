<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('tipe')->default('sekolah')->after('sekolah_id');
            // tipe: 'sekolah' = foto umum sekolah, 'fasilitas' = foto fasilitas
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
