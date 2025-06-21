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
        if (!Schema::hasTable('recyclable_material_category')) {
            Schema::create('recyclable_material_category', function (Blueprint $table) {
                $table->id();
                $table->string('name');
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
        if (Schema::hasTable('recyclable_material_category')) {
            Schema::dropIfExists('recyclable_material_category');
        }
    }
};
