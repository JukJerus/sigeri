<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    protected $guarded = ['id'];

    /**
     * Tipe foto galeri.
     */
    public const TIPE_SEKOLAH   = 'sekolah';
    public const TIPE_FASILITAS = 'fasilitas';

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    /**
     * Scope: hanya foto sekolah.
     */
    public function scopeSekolah($query)
    {
        return $query->where('tipe', self::TIPE_SEKOLAH);
    }

    /**
     * Scope: hanya foto fasilitas.
     */
    public function scopeFasilitas($query)
    {
        return $query->where('tipe', self::TIPE_FASILITAS);
    }
}
