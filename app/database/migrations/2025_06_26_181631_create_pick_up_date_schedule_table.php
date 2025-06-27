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
        if (!Schema::hasTable('pick_up_date_schedule')) {
            Schema::create('pick_up_date_schedule', function (Blueprint $table) {
                $table->id();
                $table->string('pickup_day');
                $table->integer('pick_up_id');
                $table->time('preferred_time')->nullable();
                $table->string('location');
                $table->integer('staff_id');
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
        if (Schema::hasTable('pick_up_date_schedule')) {
            Schema::dropIfExists('pick_up_date_schedule');
        }
    }
};
