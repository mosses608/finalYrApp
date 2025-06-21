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
        if (!Schema::hasTable('pickup_reminders')) {
            Schema::create('pickup_reminders', function (Blueprint $table) {
                $table->id();
                $table->integer('pickup_request_id');
                $table->dateTime('reminder_time');
                $table->boolean('sent')->default(false);
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
        if (Schema::hasTable('pickup_reminders')) {
            Schema::dropIfExists('pickup_reminders');
        }
    }
};
