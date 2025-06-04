<?php

use App\Exports\AttendanceExport;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\KaryawanImportExportController;
use App\Http\Controllers\KaryawanTemplateController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SlipGajiController;
use App\Livewire\Presensi;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('presensi', Presensi::class)->name('presensi');
    Route::get('attendance/export', function () {
        return Excel::download(new AttendanceExport, 'attendances.xlsx');
    })->name('attendance-export');

    Route::get('leave/{leave}/slip/download', [LeaveController::class, 'downloadSlip'])->name('leave.slip.download');

    // Route untuk download slip gaji
    Route::get('penggajian/{penggajian}/slip/download', [SlipGajiController::class, 'download'])->name('penggajian.slip.download');

    // Route untuk download surat sakit
    Route::get('sick-certificate/{leave}/download', [FileDownloadController::class, 'downloadSickCertificate'])
        ->name('sick-certificate.download');

    // Rute untuk import dan export data karyawan
    Route::get('karyawan/export', [KaryawanImportExportController::class, 'export'])->name('karyawan.export');
    Route::post('karyawan/import', [KaryawanImportExportController::class, 'import'])->name('karyawan.import');
    Route::get('karyawan/template', [KaryawanTemplateController::class, 'downloadTemplate'])->name('karyawan.template');
});

Route::get('/login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    return view('welcome');
});
