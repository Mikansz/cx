<?php

namespace App\Http\Controllers;

use App\Exports\KaryawanExport;
use App\Imports\KaryawanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanImportExportController extends Controller
{
    /**
     * Export data karyawan ke Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return Excel::download(new KaryawanExport, 'karyawan.xlsx');
    }

    /**
     * Import data karyawan dari Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // Log semua input untuk debugging
        Log::info('Import karyawan request:', $request->all());

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi import karyawan gagal:', $validator->errors()->toArray());

            return redirect()->route('filament.backoffice.resources.karyawans.index')
                ->withErrors($validator)
                ->with('error', 'Validasi file gagal: '.$validator->errors()->first());
        }

        if (! $request->hasFile('file')) {
            Log::error('Tidak ada file yang diupload');

            return redirect()->route('filament.backoffice.resources.karyawans.index')
                ->with('error', 'Tidak ada file yang diupload');
        }

        try {
            $file = $request->file('file');
            Log::info('File yang diupload:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ]);

            Excel::import(new KaryawanImport, $file);

            return redirect()->route('filament.backoffice.resources.karyawans.index')
                ->with('success', 'Data karyawan berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error import karyawan: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('filament.backoffice.resources.karyawans.index')
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
