<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Operator;
use App\Models\Role;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────
        $adminRole    = Role::create(['nama_role' => 'Admin Dinas']);
        $operatorRole = Role::create(['nama_role' => 'Operator Sekolah']);

        // ── Admin User ─────────────────────────────────
        User::create([
            'role_id'  => $adminRole->id,
            'username' => 'admin',
            'email'    => 'admin@sigeri.test',
            'password' => Hash::make('admin123'),
        ]);

        // ── Import CSV ─────────────────────────────────
        $csvPath = storage_path('app/import/gabungan_horizontal.csv');

        if (! file_exists($csvPath)) {
            $this->command->warn('File CSV tidak ditemukan: ' . $csvPath);
            return;
        }

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle); // baca header

        // Cache agar tidak query berulang
        $kecamatanCache = [];
        $kelurahanCache = [];
        $operatorCache  = [];  // key: nama_operator (lowercase) => Operator model
        $usernameCount  = [];  // untuk handle username duplikat

        $countSekolah   = 0;
        $countOperator  = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 29) {
                continue;
            }

            // Map kolom CSV
            $nama       = trim($row[0]);
            $npsn       = trim($row[1]);
            $desa       = trim($row[4]);
            $kecNama    = trim($row[5]);
            $alamat     = trim($row[23]);
            $akreditasi = trim($row[28]) ?: null;

            // Operator dari CSV
            $namaOperator  = trim($row[25] ?? '');
            $teleponOperator = $this->cleanPhone(trim($row[26] ?? ''));

            // Fasilitas
            $jumlahKelas        = $this->toInt($row[6]);
            $jumlahPerpus       = $this->toInt($row[7]);
            $jumlahLabKomputer  = $this->toInt($row[8]);
            $jumlahLabIpa       = $this->toInt($row[9]);
            $jumlahRuangKepsek  = $this->toInt($row[10]);
            $jumlahRuangGuru    = $this->toInt($row[11]);
            $jumlahRuangTu      = $this->toInt($row[12]);
            $jumlahWcgLaki      = $this->toInt($row[13]);
            $jumlahWcgPerempuan = $this->toInt($row[14]);
            $jumlahWcsLaki      = $this->toInt($row[15]);
            $jumlahWcsPerempuan = $this->toInt($row[16]);

            // Data sekolah
            $jumlahRombel         = $this->toInt($row[17]);
            $jumlahSiswaLaki      = $this->toInt($row[18]);
            $jumlahSiswaPerempuan = $this->toInt($row[19]);
            $jumlahGuru           = $this->toInt($row[21]);
            $jumlahTendik         = $this->toInt($row[22]);

            if (empty($npsn)) {
                continue;
            }

            // Buat/cache kecamatan
            if (! isset($kecamatanCache[$kecNama])) {
                $kecamatanCache[$kecNama] = Kecamatan::firstOrCreate(['nama' => $kecNama]);
            }
            $kecamatan = $kecamatanCache[$kecNama];

            // Buat/cache kelurahan
            $kelKey = $kecNama . '|' . $desa;
            if (! isset($kelurahanCache[$kelKey])) {
                $kelurahanCache[$kelKey] = Kelurahan::firstOrCreate([
                    'kecamatan_id' => $kecamatan->id,
                    'nama'         => $desa,
                ]);
            }
            $kelurahan = $kelurahanCache[$kelKey];

            // ── Buat/cache operator ────────────────────
            $operatorId = null;
            if (! empty($namaOperator)) {
                $opKey = Str::lower($namaOperator);

                if (! isset($operatorCache[$opKey])) {
                    // Buat username unik dari nama operator
                    $baseUsername = Str::slug($namaOperator, '_');
                    if (empty($baseUsername)) {
                        $baseUsername = 'operator';
                    }

                    // Handle username duplikat
                    $username = $baseUsername;
                    if (isset($usernameCount[$baseUsername])) {
                        $usernameCount[$baseUsername]++;
                        $username = $baseUsername . '_' . $usernameCount[$baseUsername];
                    } else {
                        $usernameCount[$baseUsername] = 1;
                    }

                    // Buat user account
                    $user = User::create([
                        'role_id'  => $operatorRole->id,
                        'username' => $username,
                        'email'    => $username . '@sigeri.test',
                        'password' => Hash::make('operator123'),
                    ]);

                    // Buat data operator
                    $operator = Operator::create([
                        'user_id' => $user->id,
                        'nama'    => $namaOperator,
                        'telepon' => $teleponOperator ?: null,
                        'alamat'  => null,
                    ]);

                    $operatorCache[$opKey] = $operator;
                    $countOperator++;
                }

                $operatorId = $operatorCache[$opKey]->id;
            }

            // Buat sekolah
            $sekolah = Sekolah::create([
                'nama'                   => $nama,
                'npsn'                   => $npsn,
                'alamat'                 => $alamat ?: null,
                'kelurahan_id'           => $kelurahan->id,
                'latitude'               => null,
                'longitude'              => null,
                'akreditasi'             => $akreditasi,
                'jumlah_rombel'          => $jumlahRombel,
                'jumlah_siswa_laki'      => $jumlahSiswaLaki,
                'jumlah_siswa_perempuan' => $jumlahSiswaPerempuan,
                'jumlah_tendik'          => $jumlahTendik,
                'jumlah_guru'            => $jumlahGuru,
                'operator_id'            => $operatorId,
            ]);

            // Buat fasilitas
            Fasilitas::create([
                'sekolah_id'           => $sekolah->id,
                'jumlah_kelas'         => $jumlahKelas,
                'jumlah_perpustakaan'  => $jumlahPerpus,
                'jumlah_lab_komputer'  => $jumlahLabKomputer,
                'jumlah_lab_ipa'       => $jumlahLabIpa,
                'jumlah_ruang_kepsek'  => $jumlahRuangKepsek,
                'jumlah_ruang_guru'    => $jumlahRuangGuru,
                'jumlah_ruang_tu'      => $jumlahRuangTu,
                'jumlah_wcg_laki'      => $jumlahWcgLaki,
                'jumlah_wcg_perempuan' => $jumlahWcgPerempuan,
                'jumlah_wcs_laki'      => $jumlahWcsLaki,
                'jumlah_wcs_perempuan' => $jumlahWcsPerempuan,
            ]);

            $countSekolah++;
        }

        fclose($handle);

        $this->command->info("Berhasil import {$countSekolah} sekolah dan {$countOperator} operator dari CSV.");
    }

    /**
     * Konversi string ke integer, return null jika kosong.
     */
    private function toInt(?string $value): ?int
    {
        $value = trim($value ?? '');
        return $value !== '' ? (int) $value : null;
    }

    /**
     * Bersihkan format nomor telepon.
     * Tambahkan prefix 0 jika diawali 8, hapus karakter non-digit.
     */
    private function cleanPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Hapus spasi, tanda hubung, tanda +
        $phone = preg_replace('/[\s\-\+]/', '', $phone);

        // Hapus karakter non-digit kecuali sudah bersih
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($phone) || $phone === '0') {
            return null;
        }

        // Ganti awalan 62 dengan 0
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        // Tambahkan 0 di depan jika diawali 8
        if (str_starts_with($phone, '8')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
