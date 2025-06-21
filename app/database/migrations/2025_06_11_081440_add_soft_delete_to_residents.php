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
        Schema::table('residents', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('residents','soft_delete')){
                $table->integer('soft_delete')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            //
             if(Schema::hasColumn('residents','soft_delete')){
                $table->dropColumn('soft_delete');
            }
        });
    }
};
