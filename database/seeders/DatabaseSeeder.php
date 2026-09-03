<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // 1. USERS (Admin & HR)
        // =========================================================
        $adminId = DB::table('users')->insertGetId([
            'name'       => 'Admin HR',
            'email'      => 'admin@hr.bingxuemixue.id',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hrId = DB::table('users')->insertGetId([
            'name'       => 'Dewi Lestari',
            'email'      => 'dewi@hr.bingxuemixue.id',
            'password'   => Hash::make('hr123456'),
            'role'       => 'hr',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Karyawan users
        $karyawanUsers = [
            ['name' => 'Ahmad Fauzi',     'email' => 'ahmad@bingxuemixue.id',   'password' => Hash::make('karyawan123')],
            ['name' => 'Siti Rahma',      'email' => 'siti@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Budi Santoso',    'email' => 'budi@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Reza Pratama',    'email' => 'reza@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Nurul Hidayah',   'email' => 'nurul@bingxuemixue.id',   'password' => Hash::make('karyawan123')],
            ['name' => 'Andi Wijaya',     'email' => 'andi@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Rina Kusuma',     'email' => 'rina@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Fajar Ramadhan',  'email' => 'fajar@bingxuemixue.id',   'password' => Hash::make('karyawan123')],
            ['name' => 'Yuni Kartika',    'email' => 'yuni@bingxuemixue.id',    'password' => Hash::make('karyawan123')],
            ['name' => 'Hendra Gunawan',  'email' => 'hendra@bingxuemixue.id',  'password' => Hash::make('karyawan123')],
        ];

        $karyawanUserIds = [];
        foreach ($karyawanUsers as $u) {
            $karyawanUserIds[] = DB::table('users')->insertGetId(array_merge($u, [
                'role'       => 'karyawan',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =========================================================
        // 2. KARYAWAN (Jabatan: Karyawan, Kepala Toko, Management)
        // =========================================================
        $dewikId = DB::table('karyawan')->insertGetId([
            'user_id'           => $hrId,
            'nik'               => 'BX-2019-000',
            'nama_lengkap'      => 'Dewi Lestari',
            'email'             => 'dewi@hr.bingxuemixue.id',
            'no_telepon'        => '+62 812-0000-0000',
            'jabatan'           => 'Management',
            'jenis_kelamin'     => 'P',
            'tanggal_lahir'     => '1989-05-20',
            'alamat'            => 'Jl. Sudirman No. 1, Jakarta Pusat',
            'tanggal_bergabung' => '2019-05-20',
            'status'            => 'aktif',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $karyawanList = [
            [
                'user_idx'  => 0,
                'nik'       => 'BX-2022-001',
                'nama'      => 'Ahmad Fauzi',
                'email'     => 'ahmad@bingxuemixue.id',
                'telepon'   => '+62 812-1111-0001',
                'jabatan'   => 'Kepala Toko',
                'jk'        => 'L',
                'tgl_lahir' => '1990-05-15',
                'alamat'    => 'Jl. Raya Tangerang No. 12, Banten',
                'tgl_gabung'=> '2022-03-01',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 1,
                'nik'       => 'BX-2021-002',
                'nama'      => 'Siti Rahma',
                'email'     => 'siti@bingxuemixue.id',
                'telepon'   => '+62 812-2222-0002',
                'jabatan'   => 'Management',
                'jk'        => 'P',
                'tgl_lahir' => '1993-08-20',
                'alamat'    => 'Jl. Kebon Jeruk No. 5, Jakarta Barat',
                'tgl_gabung'=> '2021-07-01',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 2,
                'nik'       => 'BX-2020-003',
                'nama'      => 'Budi Santoso',
                'email'     => 'budi@bingxuemixue.id',
                'telepon'   => '+62 812-3333-0003',
                'jabatan'   => 'Management',
                'jk'        => 'L',
                'tgl_lahir' => '1988-12-10',
                'alamat'    => 'Jl. Sudirman No. 88, Jakarta Pusat',
                'tgl_gabung'=> '2020-01-10',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 3,
                'nik'       => 'BX-2023-004',
                'nama'      => 'Reza Pratama',
                'email'     => 'reza@bingxuemixue.id',
                'telepon'   => '+62 812-4444-0004',
                'jabatan'   => 'Karyawan',
                'jk'        => 'L',
                'tgl_lahir' => '1995-03-22',
                'alamat'    => 'Jl. BSD Raya No. 15, Tangerang Selatan',
                'tgl_gabung'=> '2023-02-01',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 4,
                'nik'       => 'MX-2022-005',
                'nama'      => 'Nurul Hidayah',
                'email'     => 'nurul@bingxuemixue.id',
                'telepon'   => '+62 812-5555-0005',
                'jabatan'   => 'Karyawan',
                'jk'        => 'P',
                'tgl_lahir' => '1997-06-30',
                'alamat'    => 'Jl. Pahlawan No. 33, Depok',
                'tgl_gabung'=> '2022-11-15',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 5,
                'nik'       => 'BX-2021-006',
                'nama'      => 'Andi Wijaya',
                'email'     => 'andi@bingxuemixue.id',
                'telepon'   => '+62 812-6666-0006',
                'jabatan'   => 'Management',
                'jk'        => 'L',
                'tgl_lahir' => '1991-09-14',
                'alamat'    => 'Jl. Gatot Subroto No. 7, Jakarta Selatan',
                'tgl_gabung'=> '2021-04-12',
                'status'    => 'nonaktif',
            ],
            [
                'user_idx'  => 6,
                'nik'       => 'MX-2023-007',
                'nama'      => 'Rina Kusuma',
                'email'     => 'rina@bingxuemixue.id',
                'telepon'   => '+62 812-7777-0007',
                'jabatan'   => 'Karyawan',
                'jk'        => 'P',
                'tgl_lahir' => '1996-11-05',
                'alamat'    => 'Jl. Merdeka No. 21, Bogor',
                'tgl_gabung'=> '2023-06-01',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 7,
                'nik'       => 'MX-2022-008',
                'nama'      => 'Fajar Ramadhan',
                'email'     => 'fajar@bingxuemixue.id',
                'telepon'   => '+62 812-8888-0008',
                'jabatan'   => 'Kepala Toko',
                'jk'        => 'L',
                'tgl_lahir' => '1994-04-17',
                'alamat'    => 'Jl. Cempaka Putih No. 9, Jakarta Pusat',
                'tgl_gabung'=> '2022-08-01',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 8,
                'nik'       => 'BX-2023-009',
                'nama'      => 'Yuni Kartika',
                'email'     => 'yuni@bingxuemixue.id',
                'telepon'   => '+62 812-9999-0009',
                'jabatan'   => 'Karyawan',
                'jk'        => 'P',
                'tgl_lahir' => '1998-02-28',
                'alamat'    => 'Jl. Antasari No. 44, Jakarta Selatan',
                'tgl_gabung'=> '2023-01-15',
                'status'    => 'aktif',
            ],
            [
                'user_idx'  => 9,
                'nik'       => 'MX-2019-010',
                'nama'      => 'Hendra Gunawan',
                'email'     => 'hendra@bingxuemixue.id',
                'telepon'   => '+62 812-0000-0010',
                'jabatan'   => 'Management',
                'jk'        => 'L',
                'tgl_lahir' => '1985-07-11',
                'alamat'    => 'Jl. Pondok Indah No. 3, Jakarta Selatan',
                'tgl_gabung'=> '2019-03-01',
                'status'    => 'aktif',
            ],
        ];

        $karyawanIds = [];
        foreach ($karyawanList as $idx => $k) {
            $karyawanIds[$idx] = DB::table('karyawan')->insertGetId([
                'user_id'           => $karyawanUserIds[$k['user_idx']],
                'nik'               => $k['nik'],
                'nama_lengkap'      => $k['nama'],
                'email'             => $k['email'],
                'no_telepon'        => $k['telepon'],
                'jabatan'           => $k['jabatan'],
                'jenis_kelamin'     => $k['jk'],
                'tanggal_lahir'     => $k['tgl_lahir'],
                'alamat'            => $k['alamat'],
                'tanggal_bergabung' => $k['tgl_gabung'],
                'status'            => $k['status'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // =========================================================
        // 3. ABSENSI AKTUAL (30 hari terakhir untuk tren grafik akurat)
        // =========================================================
        $allStaffIds = array_merge([$dewikId], $karyawanIds);
        $today = Carbon::today();

        for ($day = 30; $day >= 0; $day--) {
            $tgl = $today->copy()->subDays($day);

            // Skip weekend
            if ($tgl->isWeekend()) continue;

            foreach ($allStaffIds as $kid) {
                // Kehadiran 85% dari total staf
                if (rand(1, 100) <= 15) continue;

                $isTerlambat = (rand(1, 10) <= 2);
                $jamMasukH = $isTerlambat ? rand(8, 9) : 8;
                $jamMasukM = $isTerlambat ? rand(16, 45) : rand(0, 14);
                $jamMasuk  = sprintf('%02d:%02d:00', $jamMasukH, $jamMasukM);

                $jamPulangH = rand(17, 18);
                $jamPulangM = rand(0, 50);
                $jamPulang  = sprintf('%02d:%02d:00', $jamPulangH, $jamPulangM);

                $mIn = $jamMasukH * 60 + $jamMasukM;
                $mOut = $jamPulangH * 60 + $jamPulangM;
                $durasi = $mOut - $mIn;

                DB::table('absensi')->insertOrIgnore([
                    'karyawan_id'  => $kid,
                    'tanggal'      => $tgl->format('Y-m-d'),
                    'jam_masuk'    => $jamMasuk,
                    'jam_pulang'   => ($day === 0) ? null : $jamPulang,
                    'lat_masuk'    => -6.2088105 + (rand(-50, 50) / 10000),
                    'lng_masuk'    => 106.8455901 + (rand(-50, 50) / 10000),
                    'lat_pulang'   => ($day === 0) ? null : (-6.2088105 + (rand(-50, 50) / 10000)),
                    'lng_pulang'   => ($day === 0) ? null : (106.8455901 + (rand(-50, 50) / 10000)),
                    'lokasi_masuk' => 'Outlet Bingxue & Mixue Jakarta',
                    'lokasi_pulang'=> ($day === 0) ? null : 'Outlet Bingxue & Mixue Jakarta',
                    'status'       => $isTerlambat ? 'terlambat' : 'hadir',
                    'durasi_menit' => ($day === 0) ? null : $durasi,
                    'catatan'      => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        // =========================================================
        // 4. CUTI & JATAH CUTI
        // =========================================================
        $cutiData = [
            [
                'karyawan_id' => $karyawanIds[0],
                'jenis'       => 'cuti_tahunan',
                'mulai'       => Carbon::now()->addDays(5)->format('Y-m-d'),
                'selesai'     => Carbon::now()->addDays(7)->format('Y-m-d'),
                'hari'        => 3,
                'alasan'      => 'Keperluan keluarga di luar kota',
                'status'      => 'pending',
            ],
            [
                'karyawan_id' => $karyawanIds[1],
                'jenis'       => 'cuti_sakit',
                'mulai'       => Carbon::now()->subDays(2)->format('Y-m-d'),
                'selesai'     => Carbon::now()->subDays(2)->format('Y-m-d'),
                'hari'        => 1,
                'alasan'      => 'Sakit flu dan demam',
                'status'      => 'disetujui',
                'disetujui_oleh' => $hrId,
            ],
            [
                'karyawan_id' => $karyawanIds[3],
                'jenis'       => 'izin',
                'mulai'       => Carbon::today()->format('Y-m-d'),
                'selesai'     => Carbon::today()->format('Y-m-d'),
                'hari'        => 1,
                'alasan'      => 'Urusan perpanjangan dokumen pribadi',
                'status'      => 'pending',
            ],
        ];

        foreach ($cutiData as $c) {
            DB::table('cuti')->insert([
                'karyawan_id'     => $c['karyawan_id'],
                'jenis'           => $c['jenis'],
                'tanggal_mulai'   => $c['mulai'],
                'tanggal_selesai' => $c['selesai'],
                'jumlah_hari'     => $c['hari'],
                'alasan'          => $c['alasan'],
                'status'          => $c['status'],
                'disetujui_oleh'  => $c['disetujui_oleh'] ?? null,
                'disetujui_at'    => isset($c['disetujui_oleh']) ? now() : null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $tahun = Carbon::now()->year;
        foreach ($allStaffIds as $kid) {
            DB::table('jatah_cuti')->insertOrIgnore([
                'karyawan_id' => $kid,
                'tahun'       => $tahun,
                'total_jatah' => 12,
                'terpakai'    => rand(0, 4),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
