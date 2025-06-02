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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('hours', 5, 2); // Total overtime hours
            $table->text('reason'); // Reason for overtime
            $table->enum('type', ['weekday', 'weekend', 'holiday'])->default('weekday'); // Type of overtime
            $table->decimal('rate_per_hour', 10, 2)->default(0); // Rate per hour for this overtime
            $table->decimal('total_amount', 12, 2)->default(0); // Total overtime payment
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->boolean('is_calculated')->default(false); // Whether included in payroll calculation
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure no duplicate overtime for same user on same date
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
