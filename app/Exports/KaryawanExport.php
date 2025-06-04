<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Karyawan::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIP',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No KTP',
            'Jenis Kelamin',
            'Agama',
            'No HP',
            'No Telepon',
            'Alamat',
            'Bank',
            'No Rekening',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * @param  mixed  $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->nip,
            $row->user->name ?? '',
            $row->tempat_lahir,
            $row->tanggal_lahir->format('d/m/Y'),
            $row->no_ktp,
            $row->jenis_kelamin,
            $row->agama,
            $row->no_hp,
            $row->no_telp,
            $row->alamat,
            $row->bank,
            $row->no_rek,
            $row->created_at->format('d/m/Y H:i:s'),
            $row->updated_at->format('d/m/Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
