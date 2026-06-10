<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kerusakan extends Model
{
    protected $guarded = ['id'];

    /**
     * Jenis fasilitas yang bisa dilaporkan kerusakannya.
     */
    public const JENIS_OPTIONS = [
        'Ruang Kelas',
        'Perpustakaan',
        'Lab Komputer',
        'Lab IPA',
        'Ruang Kepala Sekolah',
        'Ruang Guru',
        'Ruang TU',
        'WC Guru',
        'WC Siswa',
        'Lainnya',
    ];

    /**
     * Tingkat kondisi kerusakan.
     */
    public const KONDISI_OPTIONS = [
        'Ringan',
        'Sedang',
        'Berat',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
