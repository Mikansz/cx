<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class SlipGajiController extends Controller
{
    public function download(Penggajian $penggajian)
    {
        // Check authorization
        if (!$this->canAccessSlip($penggajian)) {
            abort(403, 'Unauthorized access to payslip');
        }
        
        // Check if penggajian is approved
        if ($penggajian->status !== 'approved') {
            abort(403, 'Slip gaji hanya dapat didownload setelah disetujui');
        }
        
        $penggajian->load(['karyawan.user', 'karyawan.jabatan', 'approvedBy']);
        
        $pdf = Pdf::loadView('slip-gaji', [
            'penggajian' => $penggajian,
            'karyawan' => $penggajian->karyawan,
            'bulan' => $penggajian->periode->format('F Y'),
        ]);
        
        $filename = 'Slip_Gaji_' . $penggajian->karyawan->kode_karyawan . '_' . $penggajian->periode->format('Y_m') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    private function canAccessSlip(Penggajian $penggajian): bool
    {
        $user = auth()->user();
        
        // Super admin, CFO, HRD can access all
        if ($user->hasRole(['super_admin', 'cfo', 'hrd'])) {
            return true;
        }
        
        // Karyawan can only access their own slip
        if ($user->hasRole('karyawan')) {
            return $user->karyawan?->id === $penggajian->karyawan_id;
        }
        
        return false;
    }
}
