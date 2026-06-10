<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Role;
use App\Models\User;
use App\Models\Operator;
use App\Models\Sekolah;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

#[Signature('import:sekolah {file=storage/app/import/gabungan_horizontal.csv}')]
#[Description('Import data sekolah dari CSV ke database lengkap dengan relasinya')]
class ImportSekolahCsv extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path($this->argument('file'));

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan di: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("Memulai import data sekolah dari CSV...");

        // Pastikan Role Operator ada
        $roleOperator = Role::firstOrCreate(['nama_role' => 'Operator']);

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        // Membersihkan spasi dan karakter ghaib (seperti BOM) pada awal file
        $header = array_map('trim', $header);
        $header[0] = trim($header[0], "\xef\xbb\xbf");

        $rowCount = 0;

        // Gunakan DB transaction agar jika ada error di tengah jalan, DB tidak corrupt/setengah jalan
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                // Lewati jika kolom row tidak cocok dengan header (baris kosong/rusak)
                if (count($header) !== count($row)) {
                    continue;
                }

                $data = array_combine($header, $row);
                $rowCount++;

                // 1. Relasi Kecamatan
                $kecamatanName = trim($data['Kecamatan']);
                $kecamatan = Kecamatan::firstOrCreate(['nama' => $kecamatanName]);

                // 2. Relasi Kelurahan (Desa)
                $kelurahanName = trim($data['Desa']);
                $kelurahan = Kelurahan::firstOrCreate([
                    'nama' => $kelurahanName,
                    'kecamatan_id' => $kecamatan->id
                ]);

                // 3. Relasi Operator & Sistem User Authentication
                $opName = trim($data['Nama Operator']);
                $npsn = trim($data['NPSN']);

                $operator = null;
                // Asumsi jika bukan "-" berarti ada operator
                if (!empty($opName) && $opName !== '-') {
                    $username = strtolower('op_' . $npsn);

                    // Buatkan user untuk login operator (Default password: npsn sekolah)
                    $user = User::firstOrCreate(
                        ['username' => $username],
                        [
                            'email' => $username . '@example.com',
                            'password' => Hash::make($npsn),
                            'role_id' => $roleOperator->id,
                        ]
                    );

                    $operator = Operator::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nama' => $opName,
                            'telepon' => trim($data['Nomor HP Operator']),
                        ]
                    );
                }

                // 4. Data Utama Sekolah
                $sekolah = Sekolah::updateOrCreate(
                    ['npsn' => $npsn],
                    [
                        'nama' => trim($data['Nama Satuan Pendidikan']),
                        'alamat' => trim($data['Alamat']),
                        'kelurahan_id' => $kelurahan->id,
                        'akreditasi' => trim($data['Akreditasi']),
                        'jumlah_rombel' => (int) trim($data['Jumlah Rombel']),
                        'jumlah_siswa_laki' => (int) trim($data['PD_L']),
                        'jumlah_siswa_perempuan' => (int) trim($data['PD_P']),
                        'jumlah_tendik' => (int) trim($data['Tendik']),
                        'jumlah_guru' => (int) trim($data['Guru']),
                        'operator_id' => $operator ? $operator->id : null,
                    ]
                );

                // 5. Data Fasilitas Sekolah
                Fasilitas::updateOrCreate(
                    ['sekolah_id' => $sekolah->id],
                    [
                        'jumlah_kelas' => (int) trim($data['Jumlah Ruang Kelas']),
                        'jumlah_perpustakaan' => (int) trim($data['Jumlah Ruang Perpus']),
                        'jumlah_lab_komputer' => (int) trim($data['Jumlah Lab Komputer']),
                        'jumlah_lab_ipa' => (int) trim($data['Jumlah Lab IPA']),
                        'jumlah_ruang_kepsek' => (int) trim($data['Jumlah Ruang KepSek']),
                        'jumlah_ruang_guru' => (int) trim($data['Jumlah Ruang Guru']),
                        'jumlah_ruang_tu' => (int) trim($data['Jumlah Ruang TU']),
                        'jumlah_wcg_laki' => (int) trim($data['Jumlah WC Guru Laki']),
                        'jumlah_wcg_perempuan' => (int) trim($data['Jumlah WC Guru Perempuan']),
                        'jumlah_wcs_laki' => (int) trim($data['Jumlah WC Siswa Laki']),
                        'jumlah_wcs_perempuan' => (int) trim($data['Jumlah WC Siswa Perempuan']),
                    ]
                );

                if ($rowCount % 50 === 0) {
                    $this->info("Berhasil memproses $rowCount baris...");
                }
            }

            DB::commit();
            $this->info("✅ Import selesai sukses! Total $rowCount data sekolah telah disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Gagal pada proses baris ke-$rowCount: " . $e->getMessage());
            return Command::FAILURE;
        }

        fclose($file);
        return Command::SUCCESS;
    }
}
