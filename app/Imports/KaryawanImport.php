<?php

namespace App\Imports;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KaryawanImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use Importable, SkipsFailures;

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Log data baris yang akan diimpor
            Log::info('Mengimpor data karyawan:', $row);

            // Cari user berdasarkan nama, jika tidak ada, buat user baru
            $user = User::where('name', $row['nama'])->first();

            if (! $user) {
                // Buat user baru
                Log::info('Membuat user baru:', ['nama' => $row['nama']]);

                $email = Str::slug($row['nama'], '.').'@gmail.com';
                $user = User::create([
                    'name' => $row['nama'],
                    'email' => $email,
                    'password' => Hash::make('password'), // Password default
                ]);

                // Berikan role karyawan
                $user->assignRole('karyawan');
                Log::info('User baru berhasil dibuat', ['id' => $user->id, 'email' => $email]);
            }

            // Pastikan format tanggal valid
            if (is_numeric($row['tanggal_lahir'])) {
                // Jika tanggal dalam format Excel date (numeric)
                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                // Jika tanggal dalam format string
                $tanggal_lahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
            }

            // Cari jabatan berdasarkan nama jabatan (opsional)
            $jabatan_id = null;
            if (! empty($row['jabatan'])) {
                $jabatan = Jabatan::where('nama_jabatan', $row['jabatan'])->first();
                if ($jabatan) {
                    $jabatan_id = $jabatan->id;
                }
            }

            // Convert jenis kelamin ke enum format
            $jenisKelamin = $row['jenis_kelamin'];
            if ($jenisKelamin == 'Laki-laki') {
                $jenisKelamin = 'L';
            } elseif ($jenisKelamin == 'Perempuan') {
                $jenisKelamin = 'P';
            }

            // Buat atau update data karyawan
            $karyawan = new Karyawan([
                'nip' => $row['nip'],
                'user_id' => $user->id,
                'kode_karyawan' => $kode_karyawan,
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $tanggal_lahir,
                'no_ktp' => $row['no_ktp'],
                'jenis_kelamin' => $jenisKelamin,
                'agama' => $row['agama'],
                'no_hp' => $row['no_hp'],
                'no_telp' => $row['no_telepon'] ?? null,
                'alamat' => $row['alamat'],
                'bank' => $row['bank'] ?? null,
                'no_rek' => $row['no_rekening'] ?? null,
                'jabatan_id' => $jabatan_id,
            ]);

            Log::info('Data karyawan berhasil dibuat', ['nip' => $row['nip'], 'user_id' => $user->id]);

            return $karyawan;
        } catch (Exception $e) {
            Log::error('Error saat membuat karyawan: '.$e->getMessage(), [
                'row' => $row,
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|max:12',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required',
            'no_ktp' => 'required|max:16',
            'jenis_kelamin' => 'required|in:L,P,Laki-laki,Perempuan',
            'agama' => 'required|string|max:20',
            'no_hp' => 'required|max:15',
            'alamat' => 'required|string',
            'bank' => 'nullable|max:30',
            'no_rekening' => 'nullable|max:25',
        ];
    }

    /**
     * Cast values to appropriate types before validation
     */
    public function prepareForValidation($data, $index)
    {
        // Convert numeric NIP and no_ktp to strings
        if (isset($data['nip'])) {
            $data['nip'] = (string) $data['nip'];
        }

        if (isset($data['no_ktp'])) {
            $data['no_ktp'] = (string) $data['no_ktp'];
        }

        if (isset($data['no_hp'])) {
            $data['no_hp'] = (string) $data['no_hp'];
        }

        return $data;
    }

    /**
     * Generate kode karyawan otomatis
     * Format: KRYYYXXX (KRY + 2 digit year + 3 digit sequential number)
     */
    private function generateKodeKaryawan()
    {
        $year = \Carbon\Carbon::now()->format('y'); // 2 digit year
        $prefix = 'KRY'.$year;

        // Find last employee with the same year prefix
        $lastKaryawan = Karyawan::where('kode_karyawan', 'like', $prefix.'%')
            ->orderBy('kode_karyawan', 'desc')
            ->first();

        $number = 1;

        if ($lastKaryawan) {
            // Extract the number part from the last code
            $lastNumber = substr($lastKaryawan->kode_karyawan, strlen($prefix));
            $number = intval($lastNumber) + 1;
        }

        // Pad the number with leading zeros to make it 3 digits
        $paddedNumber = str_pad($number, 3, '0', STR_PAD_LEFT);

        return $prefix.$paddedNumber; // Example: KRY25001
    }
}
