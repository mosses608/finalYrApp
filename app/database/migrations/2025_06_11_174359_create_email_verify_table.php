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
        if (!Schema::hasTable('email_verify')) {
            Schema::create('email_verify', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('token');
                $table->boolean('expired')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('email_verify')) {
            Schema::dropIfExists('email_verify');
        }
    }
};
