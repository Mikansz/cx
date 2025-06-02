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
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending')->after('total_gaji');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('status');
            $table->text('approval_note')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('approval_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approval_note', 'approved_at']);
        });
    }
};
