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
}
