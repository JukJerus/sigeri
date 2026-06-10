<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cek apakah user memiliki salah satu role yang diizinkan.
     *
     * Penggunaan di route:
     *   ->middleware('role:admin')           // hanya admin
     *   ->middleware('role:admin,operator')  // admin ATAU operator
     *
     * Nama role yang digunakan:
     *   'admin'    = Admin Dinas
     *   'operator' = Operator Sekolah
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole(...$roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
