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
        if (!Schema::hasTable('waste_schedule_pickup')) {
            Schema::create('waste_schedule_pickup', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->enum('frequency', ['once', 'daily', 'weekly', 'monthly']);
                $table->date('pickup_date');
                $table->time('preferred_time')->nullable();
                $table->string('location');
                $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
                $table->integer('soft_delete')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('waste_schedule_pickup')) {
            Schema::dropIfExists('waste_schedule_pickup');
        }
    }
};
