<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, fix existing duplicates by generating new codes
        $duplicates = \App\Models\Karyawan::select('kode_karyawan')
            ->groupBy('kode_karyawan')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('kode_karyawan');
            
        foreach ($duplicates as $duplicateCode) {
            $karyawans = \App\Models\Karyawan::where('kode_karyawan', $duplicateCode)
                ->orderBy('id')
                ->get();
                
            // Keep the first one, update the others
            foreach ($karyawans->skip(1) as $index => $karyawan) {
                $year = date('y');
                $newNumber = $karyawan->id + 1000; // Use ID + offset to ensure uniqueness
                $newCode = 'KRY' . $year . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
                $karyawan->update(['kode_karyawan' => $newCode]);
            }
        }
        
        // Now add unique constraint
        Schema::table('karyawan', function (Blueprint $table) {
            $table->unique('kode_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropUnique(['kode_karyawan']);
        });
    }
};
