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
            $table->decimal('potongan_kasbon', 12, 2)->default(0)->after('potongan_absen');
            $table->decimal('potongan_tidak_hadir', 12, 2)->default(0)->after('potongan_kasbon');
            $table->decimal('potongan_penyesuaian_lainnya', 12, 2)->default(0)->after('potongan_tidak_hadir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn(['potongan_kasbon', 'potongan_tidak_hadir', 'potongan_penyesuaian_lainnya']);
        });
    }
};
