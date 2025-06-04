<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Overtime;
use App\Models\Penggajian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SlipGajiController extends Controller
{
    public function download(Penggajian $penggajian)
    {
        // Check authorization
        if (! $this->canAccessSlip($penggajian)) {
            abort(403, 'Unauthorized access to payslip');
        }

        // Check if penggajian is approved
        if ($penggajian->status !== 'approved') {
            abort(403, 'Slip gaji hanya dapat didownload setelah disetujui');
        }

        $penggajian->load(['karyawan.user', 'karyawan.jabatan', 'approvedBy']);

        // Calculate real-time attendance data for the payroll period
        $attendanceData = $this->calculateAttendanceData($penggajian);

        $pdf = Pdf::loadView('slip-gaji', [
            'penggajian' => $penggajian,
            'karyawan' => $penggajian->karyawan,
            'bulan' => $penggajian->periode->format('F Y'),
            'attendanceData' => $attendanceData,
        ]);

        $filename = 'Slip_Gaji_'.$penggajian->karyawan->kode_karyawan.'_'.$penggajian->periode->format('Y_m').'.pdf';

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

    private function calculateAttendanceData(Penggajian $penggajian): array
    {
        $karyawan = $penggajian->karyawan;
        $userId = $karyawan->user_id;
        $periode = $penggajian->periode;

        // Calculate date range for the payroll period (entire month)
        $startOfMonth = $periode->copy()->startOfMonth();
        $endOfMonth = $periode->copy()->endOfMonth();

        // Get all attendances for the month
        $attendances = Attendance::where('user_id', $userId)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->get();

        // Get all leaves for the month
        $leaves = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                    });
            })
            ->get();

        // Get overtime records for the month
        $overtimes = Overtime::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('date', '>=', $startOfMonth)
            ->whereDate('date', '<=', $endOfMonth)
            ->get();

        // Calculate statistics
        $totalWorkingDays = $this->getWorkingDaysInMonth($startOfMonth, $endOfMonth);
        $totalPresent = $attendances->where('start_time', '!=', null)->count();
        $totalLate = $attendances->filter(function ($attendance) {
            return $attendance->isLate();
        })->count();
        $totalEarlyLeave = $attendances->filter(function ($attendance) {
            return $attendance->isEarlyLeave();
        })->count();

        // Calculate leave days by type
        $cutiDays = 0;
        $sakitDays = 0;
        $izinDays = 0;
        $totalAbsent = 0;

        foreach ($leaves as $leave) {
            $leaveDays = $this->calculateLeaveDays($leave, $startOfMonth, $endOfMonth);

            if ($leave->leave_type === Leave::CUTI_TAHUNAN ||
                $leave->leave_type === Leave::CUTI_BESAR ||
                $leave->leave_type === Leave::CUTI_PENTING ||
                $leave->leave_type === Leave::CUTI_MELAHIRKAN) {
                $cutiDays += $leaveDays;
            } elseif ($leave->leave_type === Leave::CUTI_SAKIT) {
                $sakitDays += $leaveDays;
            } elseif ($leave->leave_type === Leave::IZIN) {
                $izinDays += $leaveDays;
            }
        }

        $totalAbsent = $totalWorkingDays - $totalPresent - $cutiDays - $sakitDays - $izinDays;
        if ($totalAbsent < 0) {
            $totalAbsent = 0;
        }

        return [
            'total_working_days' => $totalWorkingDays,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'total_late' => $totalLate,
            'total_early_leave' => $totalEarlyLeave,
            'total_cuti' => $cutiDays,
            'total_sakit' => $sakitDays,
            'total_izin' => $izinDays,
            'total_overtime_hours' => $overtimes->sum('hours'),
        ];
    }

    private function getWorkingDaysInMonth(Carbon $startDate, Carbon $endDate): int
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // Exclude weekends (Saturday = 6, Sunday = 0)
            if (! $current->isWeekend()) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    private function calculateLeaveDays(Leave $leave, Carbon $monthStart, Carbon $monthEnd): int
    {
        $leaveStart = Carbon::parse($leave->start_date);
        $leaveEnd = Carbon::parse($leave->end_date);

        // Ensure dates are within the month
        $calculationStart = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
        $calculationEnd = $leaveEnd->lessThan($monthEnd) ? $leaveEnd : $monthEnd;

        if ($calculationStart > $calculationEnd) {
            return 0;
        }

        $days = 0;
        $current = $calculationStart->copy();

        while ($current <= $calculationEnd) {
            // Only count working days
            if (! $current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }
}
