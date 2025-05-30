<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            [
                'kode_jabatan' => 'DIR-001',
                'nama_jabatan' => 'Direktur Utama',
                'gaji_pokok' => 15000000,
                'tunjangan_transportasi' => 2000000,
                'tunjangan_makan' => 1500000,
            ],
            [
                'kode_jabatan' => 'MNG-001',
                'nama_jabatan' => 'Manager',
                'gaji_pokok' => 10000000,
                'tunjangan_transportasi' => 1500000,
                'tunjangan_makan' => 1000000,
            ],
            [
                'kode_jabatan' => 'SPV-001',
                'nama_jabatan' => 'Supervisor',
                'gaji_pokok' => 7500000,
                'tunjangan_transportasi' => 1000000,
                'tunjangan_makan' => 750000,
            ],
            [
                'kode_jabatan' => 'STF-001',
                'nama_jabatan' => 'Staff',
                'gaji_pokok' => 5000000,
                'tunjangan_transportasi' => 750000,
                'tunjangan_makan' => 500000,
            ],
            [
                'kode_jabatan' => 'INT-001',
                'nama_jabatan' => 'Magang',
                'gaji_pokok' => 2500000,
                'tunjangan_transportasi' => 500000,
                'tunjangan_makan' => 300000,
            ],
        ];

        foreach ($jabatan as $position) {
            Jabatan::create($position);
        }
    }
}