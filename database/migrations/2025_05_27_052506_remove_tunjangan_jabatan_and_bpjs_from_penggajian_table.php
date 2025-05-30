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
            $table->dropColumn(['tunjangan_jabatan', 'potongan_bpjs']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->decimal('tunjangan_jabatan', 12, 2)->default(0)->after('gaji_pokok');
            $table->decimal('potongan_bpjs', 12, 2)->default(0)->after('potongan_absen');
        });
    }
};
