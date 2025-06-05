<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Penggajian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DashboardTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan roles sudah ada
        $roles = ['super_admin', 'hrd', 'cfo', 'karyawan'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Buat jabatan sample jika belum ada
        $jabatanData = [
            ['kode_jabatan' => 'DIR-001', 'nama_jabatan' => 'Direktur', 'gaji_pokok' => 15000000, 'tunjangan_transportasi' => 2000000, 'tunjangan_makan' => 1500000],
            ['kode_jabatan' => 'MNG-001', 'nama_jabatan' => 'Manager', 'gaji_pokok' => 10000000, 'tunjangan_transportasi' => 1500000, 'tunjangan_makan' => 1000000],
            ['kode_jabatan' => 'STF-001', 'nama_jabatan' => 'Staff', 'gaji_pokok' => 5000000, 'tunjangan_transportasi' => 750000, 'tunjangan_makan' => 500000],
        ];

        foreach ($jabatanData as $jabatan) {
            Jabatan::firstOrCreate(['kode_jabatan' => $jabatan['kode_jabatan']], $jabatan);
        }

        // Buat users sample untuk setiap role
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin'
            ],
            [
                'name' => 'HRD Manager',
                'email' => 'hrd@example.com',
                'password' => Hash::make('password'),
                'role' => 'hrd'
            ],
            [
                'name' => 'CFO',
                'email' => 'cfo@example.com',
                'password' => Hash::make('password'),
                'role' => 'cfo'
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );

            // Assign role
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }

            // Buat data karyawan untuk user dengan role karyawan
            if ($userData['role'] === 'karyawan') {
                $jabatan = Jabatan::inRandomOrder()->first();
                
                Karyawan::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => 'NIP' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                        'kode_karyawan' => 'KRY' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                        'tempat_lahir' => 'Jakarta',
                        'tanggal_lahir' => now()->subYears(rand(25, 40)),
                        'no_ktp' => '1234567890123456',
                        'jenis_kelamin' => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                        'agama' => 'Islam',
                        'no_hp' => '08123456789',
                        'alamat' => 'Jl. Contoh No. 123',
                        'bank' => 'BCA',
                        'no_rek' => '1234567890',
                        'jabatan_id' => $jabatan->id,
                    ]
                );
            }
        }

        // Buat data attendance sample untuk 7 hari terakhir
        $karyawanUsers = User::role('karyawan')->get();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            foreach ($karyawanUsers as $user) {
                // 80% kemungkinan hadir
                if (rand(1, 100) <= 80) {
                    Attendance::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'created_at' => $date,
                        ],
                        [
                            'schedule_latitude' => -6.2088,
                            'schedule_longitude' => 106.8456,
                            'schedule_start_time' => '08:00:00',
                            'schedule_end_time' => '17:00:00',
                            'start_latitude' => -6.2088 + (rand(-10, 10) / 10000),
                            'start_longitude' => 106.8456 + (rand(-10, 10) / 10000),
                            'start_time' => '08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00',
                            'end_time' => '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00',
                            'updated_at' => $date,
                        ]
                    );
                }
            }
        }

        // Buat data cuti sample
        $leaveTypes = ['cuti_tahunan', 'cuti_sakit', 'izin'];
        $statuses = ['pending', 'approved', 'rejected'];

        foreach ($karyawanUsers as $user) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $startDate = now()->subDays(rand(1, 30));
                $endDate = $startDate->copy()->addDays(rand(1, 5));
                
                Leave::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'start_date' => $startDate->format('Y-m-d'),
                    ],
                    [
                        'leave_type' => $leaveTypes[array_rand($leaveTypes)],
                        'end_date' => $endDate->format('Y-m-d'),
                        'reason' => 'Keperluan pribadi',
                        'status' => $statuses[array_rand($statuses)],
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ]
                );
            }
        }

        // Buat data penggajian sample
        $karyawans = Karyawan::with('jabatan')->get();
        
        foreach ($karyawans as $karyawan) {
            if ($karyawan->jabatan) {
                // Gaji bulan ini
                Penggajian::firstOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'periode' => now()->startOfMonth(),
                    ],
                    [
                        'gaji_pokok' => $karyawan->jabatan->gaji_pokok,
                        'tunjangan_transport' => $karyawan->jabatan->tunjangan_transportasi,
                        'tunjangan_makan' => $karyawan->jabatan->tunjangan_makan,
                        'tunjangan_komunikasi' => 200000,
                        'tunjangan_kesehatan' => 300000,
                        'jumlah_hadir' => rand(20, 22),
                        'total_gaji' => $karyawan->jabatan->gaji_pokok + 
                                      $karyawan->jabatan->tunjangan_transportasi + 
                                      $karyawan->jabatan->tunjangan_makan + 
                                      200000 + 300000,
                        'status' => 'approved',
                        'created_at' => now()->startOfMonth(),
                        'updated_at' => now()->startOfMonth(),
                    ]
                );

                // Gaji bulan lalu
                $lastMonth = now()->subMonth();
                Penggajian::firstOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'periode' => $lastMonth->startOfMonth(),
                    ],
                    [
                        'gaji_pokok' => $karyawan->jabatan->gaji_pokok,
                        'tunjangan_transport' => $karyawan->jabatan->tunjangan_transportasi,
                        'tunjangan_makan' => $karyawan->jabatan->tunjangan_makan,
                        'tunjangan_komunikasi' => 200000,
                        'tunjangan_kesehatan' => 300000,
                        'jumlah_hadir' => rand(20, 22),
                        'total_gaji' => $karyawan->jabatan->gaji_pokok + 
                                      $karyawan->jabatan->tunjangan_transportasi + 
                                      $karyawan->jabatan->tunjangan_makan + 
                                      200000 + 300000,
                        'status' => 'approved',
                        'created_at' => $lastMonth->startOfMonth(),
                        'updated_at' => $lastMonth->startOfMonth(),
                    ]
                );
            }
        }

        $this->command->info('Dashboard test data created successfully!');
        $this->command->info('Test users created:');
        $this->command->info('- admin@example.com (Super Admin)');
        $this->command->info('- hrd@example.com (HRD)');
        $this->command->info('- cfo@example.com (CFO)');
        $this->command->info('- john@example.com (Karyawan)');
        $this->command->info('- jane@example.com (Karyawan)');
        $this->command->info('Password for all: password');
    }
}
