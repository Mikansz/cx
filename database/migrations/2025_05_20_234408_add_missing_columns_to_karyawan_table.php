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
        Schema::table('karyawan', function (Blueprint $table) {
            // Tambahkan kolom-kolom yang hilang
            if (!Schema::hasColumn('karyawan', 'no_ktp')) {
                $table->string('no_ktp')->after('tanggal_lahir');
            }
            
            if (!Schema::hasColumn('karyawan', 'agama')) {
                $table->string('agama')->after('jenis_kelamin');
            }
            
            if (!Schema::hasColumn('karyawan', 'no_telp')) {
                $table->string('no_telp')->nullable()->after('no_hp');
            }
            
            if (!Schema::hasColumn('karyawan', 'bank')) {
                $table->string('bank')->nullable()->after('alamat');
            }
            
            if (!Schema::hasColumn('karyawan', 'no_rek')) {
                $table->string('no_rek')->nullable()->after('bank');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn(['no_ktp', 'agama', 'no_telp', 'bank', 'no_rek']);
        });
    }
};
