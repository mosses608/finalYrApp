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
        if (!Schema::hasTable('recyclables')) {
            Schema::create('recyclables', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title');
                $table->integer('material_type');
                $table->decimal('weight', 8, 2);
                $table->decimal('price', 10, 2);
                $table->string('image')->nullable();
                $table->text('description')->nullable();
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
        if (Schema::hasTable('recyclables')) {
            Schema::dropIfExists('recyclables');
        }
    }
};
