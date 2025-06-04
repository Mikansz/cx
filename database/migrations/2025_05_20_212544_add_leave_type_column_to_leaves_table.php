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
        if (Schema::hasTable('leaves') && ! Schema::hasColumn('leaves', 'leave_type')) {
            Schema::table('leaves', function (Blueprint $table) {
                $table->enum('leave_type', ['cuti_tahunan', 'cuti_sakit', 'cuti_melahirkan', 'cuti_penting', 'cuti_besar'])
                    ->default('cuti_tahunan')
                    ->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leaves') && Schema::hasColumn('leaves', 'leave_type')) {
            Schema::table('leaves', function (Blueprint $table) {
                $table->dropColumn('leave_type');
            });
        }
    }
};
