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
        Schema::table('payments', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('payments','mode')){
                $table->enum('mode', ['wallet','online pay'])->default('wallet');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
             if(Schema::hasColumn('payments','mode')){
                $table->dropColumn('mode');
            }
        });
    }
};
