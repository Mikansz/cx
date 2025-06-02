<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'izin' to the leave_type enum
        DB::statement("ALTER TABLE leaves MODIFY COLUMN leave_type ENUM('cuti_tahunan', 'cuti_sakit', 'cuti_melahirkan', 'cuti_penting', 'cuti_besar', 'izin') DEFAULT 'cuti_tahunan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'izin' from the leave_type enum
        DB::statement("ALTER TABLE leaves MODIFY COLUMN leave_type ENUM('cuti_tahunan', 'cuti_sakit', 'cuti_melahirkan', 'cuti_penting', 'cuti_besar') DEFAULT 'cuti_tahunan'");
    }
};
