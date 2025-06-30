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
        if (!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('recyclable_id')->constrained('recyclables');
                $table->foreignId('buyer_id')->constrained('users');
                $table->foreignId('seller_id')->constrained('users');
                $table->decimal('price_usd', 10, 2);
                $table->string('status')->default('Pending'); // or: Pending, Approved, Completed, Rejected
                $table->json('blockchain_data')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::dropIfExists('contracts');
        }
    }
};
