<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Role;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Daftar semua operator beserta sekolah yang ditugaskan.
     */
    public function index(Request $request)
    {
        $query = Operator::with(['user', 'sekolah']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%{$search}%"));
            });
        }

        $operators = $query->latest()->paginate(15)->withQueryString();

        return view('pages.operator.index', [
            'active'    => 'operator',
            'operators' => $operators,
            'search'    => $request->search,
        ]);
    }

    /**
     * Form tambah operator baru.
     */
    public function create()
    {
        $sekolahs = Sekolah::whereNull('operator_id')
            ->orderBy('nama')
            ->get();

        return view('pages.operator.create', [
            'active'   => 'operator',
            'sekolahs' => $sekolahs,
        ]);
    }

    /**
     * Simpan operator baru beserta akun user-nya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username',
            'email'      => 'required|email|max:255|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'telepon'    => 'nullable|string|max:20',
            'alamat'     => 'nullable|string|max:500',
            'sekolah_ids' => 'nullable|array',
            'sekolah_ids.*' => 'exists:sekolahs,id',
        ], [
            'nama.required'      => 'Nama operator wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request) {
            // Buat akun user dengan role Operator Sekolah
            $operatorRole = Role::where('nama_role', 'Operator Sekolah')->first();

            $user = User::create([
                'role_id'  => $operatorRole->id,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Buat data operator
            $operator = Operator::create([
                'user_id' => $user->id,
                'nama'    => $request->nama,
                'telepon' => $request->telepon,
                'alamat'  => $request->alamat,
            ]);

            // Assign sekolah ke operator
            if ($request->filled('sekolah_ids')) {
                Sekolah::whereIn('id', $request->sekolah_ids)
                    ->update(['operator_id' => $operator->id]);
            }
        });

        return redirect()->route('operator.index')
            ->with('success', 'Operator berhasil ditambahkan.');
    }

    /**
     * Form edit operator.
     */
    public function edit($id)
    {
        $operator = Operator::with(['user', 'sekolah'])->findOrFail($id);

        // Sekolah yg belum punya operator + sekolah milik operator ini
        $sekolahs = Sekolah::where(function ($q) use ($operator) {
            $q->whereNull('operator_id')
              ->orWhere('operator_id', $operator->id);
        })->orderBy('nama')->get();

        return view('pages.operator.edit', [
            'active'   => 'operator',
            'operator' => $operator,
            'sekolahs' => $sekolahs,
        ]);
    }

    /**
     * Update data operator.
     */
    public function update(Request $request, $id)
    {
        $operator = Operator::with('user')->findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username,' . $operator->user_id,
            'email'      => 'required|email|max:255|unique:users,email,' . $operator->user_id,
            'password'   => 'nullable|string|min:6|confirmed',
            'telepon'    => 'nullable|string|max:20',
            'alamat'     => 'nullable|string|max:500',
            'sekolah_ids' => 'nullable|array',
            'sekolah_ids.*' => 'exists:sekolahs,id',
        ], [
            'nama.required'      => 'Nama operator wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request, $operator) {
            // Update user
            $userData = [
                'username' => $request->username,
                'email'    => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $operator->user->update($userData);

            // Update operator
            $operator->update([
                'nama'    => $request->nama,
                'telepon' => $request->telepon,
                'alamat'  => $request->alamat,
            ]);

            // Reset semua sekolah lama, lalu assign yang baru
            Sekolah::where('operator_id', $operator->id)
                ->update(['operator_id' => null]);

            if ($request->filled('sekolah_ids')) {
                Sekolah::whereIn('id', $request->sekolah_ids)
                    ->update(['operator_id' => $operator->id]);
            }
        });

        return redirect()->route('operator.index')
            ->with('success', 'Data operator berhasil diperbarui.');
    }

    /**
     * Hapus operator beserta akun user-nya.
     */
    public function destroy($id)
    {
        $operator = Operator::findOrFail($id);

        DB::transaction(function () use ($operator) {
            // Lepas semua sekolah dari operator ini
            Sekolah::where('operator_id', $operator->id)
                ->update(['operator_id' => null]);

            // Hapus user (operator otomatis terhapus via cascade)
            User::destroy($operator->user_id);
        });

        return redirect()->route('operator.index')
            ->with('success', 'Operator berhasil dihapus.');
    }
}
