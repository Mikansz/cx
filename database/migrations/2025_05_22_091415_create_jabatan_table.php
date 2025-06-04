<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jabatan')->unique();
            $table->string('nama_jabatan');
            $table->decimal('gaji_pokok', 12, 2);
            $table->decimal('tunjangan_transportasi', 12, 2);
            $table->decimal('tunjangan_makan', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};
