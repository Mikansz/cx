<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KaryawanImportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return array
    */
    public function array(): array
    {
        // Contoh data untuk template
        return [
            [
                'nip' => '123456789',
                'nama' => 'Nama Karyawan',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'no_ktp' => '1234567890123456',
                'jenis_kelamin' => 'L', // L=Laki-laki, P=Perempuan
                'agama' => 'Islam', // atau agama lainnya
                'no_hp' => '081234567890',
                'no_telepon' => '021-1234567',
                'alamat' => 'Jl. Contoh No. 123, Jakarta',
                'bank' => 'BCA', // opsional
                'no_rekening' => '1234567890', // opsional
                'jabatan' => 'Staff', // opsional, sesuai nama jabatan di database
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nip',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'no_ktp',
            'jenis_kelamin',
            'agama',
            'no_hp',
            'no_telepon',
            'alamat',
            'bank',
            'no_rekening',
            'jabatan',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
