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
        if (!Schema::hasTable('wallet_recharge_transaction')) {
            Schema::create('wallet_recharge_transaction', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('payer_id');
                $table->string('payer_email');
                $table->decimal('amount', 15, 2);
                $table->string('currency')->nullable();
                $table->string('status');
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
        if (Schema::hasTable('wallet_recharge_transaction')) {
            Schema::dropIfExists('wallet_recharge_transaction');
        }
    }
};
