<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function downloadSlip(Leave $leave)
    {
        // Pastikan user hanya bisa mengunduh slip cuti miliknya sendiri atau user adalah admin
        if (auth()->user()->id !== $leave->user_id && !auth()->user()->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan status cuti sudah disetujui
        if ($leave->status !== 'approved') {
            abort(403, 'Slip cuti hanya tersedia untuk cuti yang sudah disetujui.');
        }

        // Mengkonversi tanggal ke Carbon jika belum
        $start_date = $leave->start_date instanceof Carbon ? $leave->start_date : Carbon::parse($leave->start_date);
        $end_date = $leave->end_date instanceof Carbon ? $leave->end_date : Carbon::parse($leave->end_date);
        
        $data = [
            'leave' => $leave,
            'user' => $leave->user,
            'date' => now()->format('d F Y'),
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        // Render view ke HTML
        $html = View::make('pdf.leave-slip', $data)->render();
        
        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Stream PDF ke browser dengan nama file
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="slip-cuti-' . $leave->id . '.pdf"');
    }
} 