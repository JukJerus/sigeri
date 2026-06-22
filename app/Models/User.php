<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['role_id', 'username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function operator(): HasOne
    {
        return $this->hasOne(Operator::class);
    }

    // ── Role Helpers ───────────────────────────────

    /**
     * Mapping slug role ke nama di database.
     */
    private const ROLE_MAP = [
        'admin'    => 'Admin Dinas',
        'operator' => 'Operator Sekolah',
    ];

    /**
     * Cek apakah user adalah Admin Dinas.
     */
    public function isAdmin(): bool
    {
        return $this->role?->nama_role === self::ROLE_MAP['admin'];
    }

    /**
     * Cek apakah user adalah Operator Sekolah.
     */
    public function isOperator(): bool
    {
        return $this->role?->nama_role === self::ROLE_MAP['operator'];
    }

    /**
     * Cek apakah user memiliki salah satu role.
     * Contoh: $user->hasRole('admin', 'operator')
     */
    public function hasRole(string ...$slugs): bool
    {
        $allowedNames = array_map(
            fn($s) => self::ROLE_MAP[$s] ?? $s,
            $slugs
        );

        return in_array($this->role?->nama_role, $allowedNames, true);
    }

    // ── Akses Sekolah ─────────────────────────────────

    /**
     * Cek apakah user boleh mengakses sekolah tertentu.
     *
     * - Admin: boleh akses SEMUA sekolah.
     * - Operator: hanya sekolah yang operator_id-nya milik dia.
     *
     * Gunakan method ini di setiap controller yang menyentuh data sekolah:
     *   if (! $user->canAccessSekolah($sekolahId)) abort(403);
     */
    public function canAccessSekolah(int $sekolahId): bool
    {
        // Admin boleh semua
        if ($this->isAdmin()) {
            return true;
        }

        // Operator cek apakah sekolah ditugaskan padanya
        if ($this->isOperator()) {
            return Sekolah::where('id', $sekolahId)
                ->where('operator_id', $this->operator?->id)
                ->exists();
        }

        return false;
    }

    /**
     * Ambil ID sekolah yang boleh diakses user.
     *
     * - Admin: null (artinya semua, jangan filter).
     * - Operator: array ID sekolah miliknya.
     *
     * Contoh pakai di query:
     *   $ids = $user->getSekolahIds();
     *   $query->when($ids !== null, fn($q) => $q->whereIn('sekolah_id', $ids));
     */
    public function getSekolahIds(): ?array
    {
        if ($this->isAdmin()) {
            return null; // null = semua
        }

        return Sekolah::where('operator_id', $this->operator?->id)
            ->pluck('id')
            ->toArray();
    }
}
