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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('no_ktp');
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->string('no_hp');
            $table->string('no_telp')->nullable();
            $table->text('alamat');
            $table->string('bank')->nullable();
            $table->string('no_rek')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
