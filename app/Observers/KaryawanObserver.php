<?php

namespace App\Observers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Office;
use Carbon\Carbon;

class KaryawanObserver
{
    /**
     * Handle the Karyawan "creating" event.
     */
    public function creating(Karyawan $karyawan): void
    {
        // Generate kode karyawan if not provided
        if (empty($karyawan->kode_karyawan)) {
            $karyawan->kode_karyawan = $this->generateKodeKaryawan();
        }
        
        // Assign default jabatan if not provided
        if (empty($karyawan->jabatan_id)) {
            // Default to Staff position (ID 4) or the first available position
            $defaultJabatan = Jabatan::where('nama_jabatan', 'Staff')->first() ?? Jabatan::first();
            
            if ($defaultJabatan) {
                $karyawan->jabatan_id = $defaultJabatan->id;
            }
        }
    }
    
    /**
     * Handle the Karyawan "created" event.
     */
    public function created(Karyawan $karyawan): void
    {
        // Check if user already has a schedule
        $existingSchedule = Schedule::where('user_id', $karyawan->user_id)->first();
        
        if ($existingSchedule) {
            // User already has a schedule, don't create a new one
            return;
        }
        
        // Get the morning shift (assuming ID 1 or finding by name 'Pagi')
        $morningShift = Shift::where('name', 'Pagi')->first();
        
        // If morning shift not found, get the first shift as default
        if (!$morningShift) {
            $morningShift = Shift::first();
        }
        
        // If no shifts exist at all, return without creating a schedule
        if (!$morningShift) {
            return;
        }
        
        // Get the first office as default
        $defaultOffice = Office::first();
        
        // If no offices exist, return without creating a schedule
        if (!$defaultOffice) {
            return;
        }
        
        // Create default schedule with morning shift for the new employee
        try {
            Schedule::create([
                'user_id' => $karyawan->user_id,
                'shift_id' => $morningShift->id,
                'office_id' => $defaultOffice->id,
                'is_wfa' => false,
                'is_banned' => false,
            ]);
        } catch (\Exception $e) {
            // If schedule creation fails (e.g., duplicate), just continue
            // The karyawan is still created successfully
            return;
        }
    }
    
    /**
     * Generate a unique employee code
     * Format: KRYYYXXX (KRY + 2 digit year + 3 digit sequential number)
     * Total: 8 characters (fits in 15 char limit)
     */
    private function generateKodeKaryawan(): string
    {
        $year = Carbon::now()->format('y'); // 2 digit year
        $prefix = 'KRY' . $year;
        
        $attempts = 0;
        $maxAttempts = 100;
        
        do {
            // Find last employee with the same year prefix
            $lastKaryawan = Karyawan::where('kode_karyawan', 'like', $prefix . '%')
                ->orderBy('kode_karyawan', 'desc')
                ->first();
            
            $number = 1;
            
            if ($lastKaryawan) {
                // Extract the number part from the last code
                $lastNumber = substr($lastKaryawan->kode_karyawan, strlen($prefix));
                $number = intval($lastNumber) + 1;
            }
            
            // Add attempt offset to avoid duplicates in concurrent requests
            $number += $attempts;
            
            // Pad the number with leading zeros to make it 3 digits
            $paddedNumber = str_pad($number, 3, '0', STR_PAD_LEFT);
            
            $kodeKaryawan = $prefix . $paddedNumber; // Example: KRY25001
            
            // Check if this code already exists
            $exists = Karyawan::where('kode_karyawan', $kodeKaryawan)->exists();
            
            if (!$exists) {
                return $kodeKaryawan;
            }
            
            $attempts++;
        } while ($attempts < $maxAttempts);
        
        // Fallback: use timestamp if all attempts failed
        return $prefix . substr(time(), -3);
    }
}