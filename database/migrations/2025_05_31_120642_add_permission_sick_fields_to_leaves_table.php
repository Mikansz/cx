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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('permission_type')->nullable()->after('leave_type'); // Jenis izin spesifik
            $table->time('permission_start_time')->nullable()->after('start_date'); // Waktu mulai izin
            $table->time('permission_end_time')->nullable()->after('permission_start_time'); // Waktu selesai izin
            $table->string('sick_certificate')->nullable()->after('reason'); // File surat sakit
            $table->text('symptoms')->nullable()->after('sick_certificate'); // Gejala penyakit
            $table->string('doctor_name')->nullable()->after('symptoms'); // Nama dokter
            $table->string('hospital_clinic')->nullable()->after('doctor_name'); // Nama RS/Klinik
            $table->boolean('is_emergency')->default(false)->after('hospital_clinic'); // Izin darurat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'permission_type',
                'permission_start_time', 
                'permission_end_time',
                'sick_certificate',
                'symptoms',
                'doctor_name',
                'hospital_clinic',
                'is_emergency'
            ]);
        });
    }
};
