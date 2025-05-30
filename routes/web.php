<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Excel;
use App\Livewire\Presensi;
use App\Exports\AttendanceExport;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\KaryawanImportExportController;
use App\Http\Controllers\KaryawanTemplateController;

Route::group(['middleware' => 'auth'], function() {
    Route::get('presensi', Presensi::class)->name('presensi');
    Route::get('attendance/export', function () {
        return Excel::download(new AttendanceExport, 'attendances.xlsx');
    })->name('attendance-export');
    
    Route::get('leave/{leave}/slip/download', [LeaveController::class, 'downloadSlip'])->name('leave.slip.download');

    
    // Rute untuk import dan export data karyawan
    Route::get('karyawan/export', [KaryawanImportExportController::class, 'export'])->name('karyawan.export');
    Route::post('karyawan/import', [KaryawanImportExportController::class, 'import'])->name('karyawan.import');
    Route::get('karyawan/template', [KaryawanTemplateController::class, 'downloadTemplate'])->name('karyawan.template');
});

Route::get('/login', function() {
    return redirect('admin/login');
})->name('login');



Route::get('/', function () {
    return view('welcome');
});
