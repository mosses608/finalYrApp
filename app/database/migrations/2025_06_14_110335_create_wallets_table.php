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
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->decimal('balance', 15, 2)->default(0);
                $table->string('currency', 3)->default('TZS');
                $table->enum('status', ['active', 'suspended', 'closed'])->default('active');
                $table->string('wallet_type')->nullable();
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
        if (Schema::hasTable('wallets')) {
            Schema::dropIfExists('wallets');
        }
    }
};
