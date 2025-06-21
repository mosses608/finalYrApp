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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->integer('pick_up_id');
                $table->string('user_email');
                $table->string('payment_id')->unique();
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
        if (Schema::hasTable('payments')) {
            Schema::dropIfExists('payments');
        }
    }
};
