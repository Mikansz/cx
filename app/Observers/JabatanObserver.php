<?php

namespace App\Observers;

use App\Models\Jabatan;

class JabatanObserver
{
    /**
     * Handle the Jabatan "creating" event.
     */
    public function creating(Jabatan $jabatan): void
    {
        if (empty($jabatan->kode_jabatan)) {
            $jabatan->kode_jabatan = $this->generateKodeJabatan();
        }
    }

    /**
     * Generate unique kode jabatan with format JBT25001, JBT25002, etc.
     */
    private function generateKodeJabatan(): string
    {
        $year = date('y'); // Get last 2 digits of current year
        $prefix = "JBT{$year}";
        
        // Get the last jabatan code for current year
        $lastJabatan = Jabatan::where('kode_jabatan', 'LIKE', "{$prefix}%")
            ->orderBy('kode_jabatan', 'desc')
            ->first();
            
        if ($lastJabatan) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastJabatan->kode_jabatan, -3);
            $newNumber = $lastNumber + 1;
        } else {
            // First jabatan for this year
            $newNumber = 1;
        }
        
        // Format with leading zeros (3 digits)
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
