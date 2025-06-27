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
        if (!Schema::hasTable('pickup_management')) {
            Schema::create('pickup_management', function (Blueprint $table) {
                $table->id();
                $table->string('pick_up_name');
                $table->string('reg_number')->nullable();
                $table->integer('added_by');
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
        if (Schema::hasTable('pickup_management')) {
            Schema::dropIfExists('pickup_management');
        }
    }
};
