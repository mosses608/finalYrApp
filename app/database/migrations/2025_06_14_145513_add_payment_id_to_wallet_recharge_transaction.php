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
        Schema::table('wallet_recharge_transaction', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('wallet_recharge_transaction','payment_id')){
                $table->string('payment_id')->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_recharge_transaction', function (Blueprint $table) {
            //
            if(Schema::hasColumn('wallet_recharge_transaction','payment_id')){
                $table->dropColumn('payment_id');
            }
        });
    }
};
