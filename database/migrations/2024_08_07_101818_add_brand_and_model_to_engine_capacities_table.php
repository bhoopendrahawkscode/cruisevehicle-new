<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('engine_capacities', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('name');
            $table->unsignedBigInteger('model_id')->nullable()->after('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engine_capacities', function (Blueprint $table) {
            $table->dropColumn(['brand_id', 'model_id']);
        });
    }
};
