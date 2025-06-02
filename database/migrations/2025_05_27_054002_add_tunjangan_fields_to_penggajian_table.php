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
        Schema::table('penggajian', function (Blueprint $table) {
            if (!Schema::hasColumn('penggajian', 'tunjangan_komunikasi')) {
                $table->decimal('tunjangan_komunikasi', 12, 2)->default(0)->after('tunjangan_makan');
            }
            if (!Schema::hasColumn('penggajian', 'tunjangan_kesehatan')) {
                $table->decimal('tunjangan_kesehatan', 12, 2)->default(0)->after('tunjangan_komunikasi');
            }
            if (!Schema::hasColumn('penggajian', 'tunjangan_lembur')) {
                $table->decimal('tunjangan_lembur', 12, 2)->default(0)->after('tunjangan_kesehatan');
            }
            if (!Schema::hasColumn('penggajian', 'tunjangan_hari_raya')) {
                $table->decimal('tunjangan_hari_raya', 12, 2)->default(0)->after('tunjangan_lembur');
            }
            if (!Schema::hasColumn('penggajian', 'tunjangan_insentif')) {
                $table->decimal('tunjangan_insentif', 12, 2)->default(0)->after('tunjangan_hari_raya');
            }
            if (!Schema::hasColumn('penggajian', 'tunjangan_lainnya')) {
                $table->decimal('tunjangan_lainnya', 12, 2)->default(0)->after('tunjangan_insentif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn(['tunjangan_komunikasi', 'tunjangan_kesehatan', 'tunjangan_lembur', 'tunjangan_hari_raya', 'tunjangan_insentif', 'tunjangan_lainnya']);
        });
    }
};
