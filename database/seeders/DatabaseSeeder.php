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

        // ── Operator User ──────────────────────────────
        $operatorUser = User::create([
            'role_id'  => $operatorRole->id,
            'username' => 'operator',
            'email'    => 'operator@sigeri.test',
            'password' => Hash::make('operator123'),
        ]);

        Operator::create([
            'user_id' => $operatorUser->id,
            'nama'    => 'Operator Demo',
            'telepon' => '081234567890',
            'alamat'  => 'Kota Bekasi',
        ]);

        // ── Import CSV ─────────────────────────────────
        $csvPath = storage_path('app/import/gabungan_horizontal.csv');

        if (! file_exists($csvPath)) {
            $this->command->warn('File CSV tidak ditemukan: ' . $csvPath);
            return;
        }

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle); // baca header

        // Cache kecamatan & kelurahan agar tidak query berulang
        $kecamatanCache = [];
        $kelurahanCache = [];

        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 29) {
                continue; // skip baris tidak lengkap
            }

            // Map kolom CSV ke variabel
            $nama       = trim($row[0]);
            $npsn       = trim($row[1]);
            $desa       = trim($row[4]);  // Kelurahan/Desa
            $kecNama    = trim($row[5]);  // Kecamatan
            $alamat     = trim($row[23]);
            $akreditasi = trim($row[28]) ?: null;

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

            // Skip jika NPSN kosong
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
                'operator_id'            => null,
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

            $count++;
        }

        fclose($handle);

        $this->command->info("Berhasil import {$count} sekolah dari CSV.");
    }

    /**
     * Konversi string ke integer, return null jika kosong.
     */
    private function toInt(?string $value): ?int
    {
        $value = trim($value ?? '');
        return $value !== '' ? (int) $value : null;
    }
}
