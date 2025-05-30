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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->date('periode')->comment('Bulan periode penggajian');
            $table->decimal('gaji_pokok', 12, 2)->default(0);
            
            // Tunjangan/Allowances
            $table->decimal('tunjangan_transport', 12, 2)->default(0);
            $table->decimal('tunjangan_makan', 12, 2)->default(0);
            $table->decimal('tunjangan_komunikasi', 12, 2)->default(0);
            $table->decimal('tunjangan_kesehatan', 12, 2)->default(0);
            $table->decimal('tunjangan_lembur', 12, 2)->default(0);
            $table->decimal('tunjangan_hari_raya', 12, 2)->default(0);
            $table->decimal('tunjangan_insentif', 12, 2)->default(0);
            $table->decimal('tunjangan_lainnya', 12, 2)->default(0);
            
            // Overtime data
            $table->integer('jam_lembur')->default(0)->comment('Total jam lembur');
            $table->integer('jumlah_hadir')->default(0)->comment('Total hari hadir');
            
            // Deductions
            $table->decimal('potongan_absen', 12, 2)->default(0);
            $table->decimal('potongan_kasbon', 12, 2)->default(0);
            $table->decimal('potongan_tidak_hadir', 12, 2)->default(0);
            $table->decimal('potongan_penyesuaian_lainnya', 12, 2)->default(0);
            $table->decimal('potongan_pph21', 12, 2)->default(0);
            
            $table->decimal('total_gaji', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
