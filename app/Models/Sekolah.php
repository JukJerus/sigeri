<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Sekolah extends Model
{
    protected $guarded = ['id'];

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * Akses kecamatan melalui kelurahan.
     * sekolah.kelurahan_id → kelurahan.kecamatan_id → kecamatan.id
     */
    public function kecamatan(): HasOneThrough
    {
        return $this->hasOneThrough(
            Kecamatan::class,
            Kelurahan::class,
            'id',             // kelurahan.id (cocokkan dengan sekolah.kelurahan_id)
            'id',             // kecamatan.id (cocokkan dengan kelurahan.kecamatan_id)
            'kelurahan_id',   // FK di sekolah
            'kecamatan_id'    // FK di kelurahan
        );
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function fasilitas(): HasOne
    {
        return $this->hasOne(Fasilitas::class);
    }

    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }

    public function kerusakan(): HasMany
    {
        return $this->hasMany(Kerusakan::class);
    }
}
