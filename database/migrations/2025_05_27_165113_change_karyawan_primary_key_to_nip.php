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
        // Update NULL values first
        \DB::table('karyawan')->whereNull('kode_karyawan')->orWhere('kode_karyawan', '')->update(['kode_karyawan' => 'KRY25999']);

        Schema::table('karyawan', function (Blueprint $table) {
            // Ubah tipe data kolom yang perlu - nullable dulu untuk menghindari error
            $table->string('nip', 12)->nullable()->change();
            $table->string('kode_karyawan', 15)->nullable()->change();
            $table->string('tempat_lahir', 50)->nullable()->change();
            $table->string('no_ktp', 16)->nullable()->change();
            $table->string('agama', 20)->nullable()->change();
            $table->string('no_hp', 15)->nullable()->change();
            $table->string('no_telp', 15)->nullable()->change();
            $table->string('bank', 30)->nullable()->change();
            $table->string('no_rek', 25)->nullable()->change();
        });

        // Update jenis kelamin data dari string ke enum
        \DB::table('karyawan')->where('jenis_kelamin', 'Laki-laki')->update(['jenis_kelamin' => 'L']);
        \DB::table('karyawan')->where('jenis_kelamin', 'Perempuan')->update(['jenis_kelamin' => 'P']);

        Schema::table('karyawan', function (Blueprint $table) {
            // Ubah jenis_kelamin ke enum setelah data diupdate
            \DB::statement("ALTER TABLE karyawan MODIFY jenis_kelamin ENUM('L', 'P') NOT NULL COMMENT 'L=Laki-laki, P=Perempuan'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update enum back to string values
        \DB::table('karyawan')->where('jenis_kelamin', 'L')->update(['jenis_kelamin' => 'Laki-laki']);
        \DB::table('karyawan')->where('jenis_kelamin', 'P')->update(['jenis_kelamin' => 'Perempuan']);

        Schema::table('karyawan', function (Blueprint $table) {
            // Revert column types
            $table->string('jenis_kelamin')->change();
            $table->string('nip')->change();
            $table->string('kode_karyawan')->change();
            $table->string('tempat_lahir')->change();
            $table->string('no_ktp')->change();
            $table->string('agama')->change();
            $table->string('no_hp')->change();
            $table->string('no_telp')->nullable()->change();
            $table->string('bank')->nullable()->change();
            $table->string('no_rek')->nullable()->change();
        });
    }
};
