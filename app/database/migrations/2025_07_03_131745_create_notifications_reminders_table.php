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
        if (!Schema::hasTable('notifications_reminders')) {
            Schema::create('notifications_reminders', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->text('title');
                $table->longText('message_body');
                $table->integer('sent_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications_reminders')) {
            Schema::dropIfExists('notifications_reminders');
        }
    }
};
