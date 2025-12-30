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
        Schema::table('insurance_renewal', function (Blueprint $table) {
            $table->string('vehicle_insurer')->change();
            $table->string('vehicle_not_use')->change();
            $table->string('vehicle_accidents')->change();
            $table->string('vehicle_experience')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_renewal', function (Blueprint $table) {
            $table->tinyInteger('vehicle_insurer')->change();
            $table->tinyInteger('vehicle_not_use')->change();
            $table->tinyInteger('vehicle_accidents')->change();
            $table->tinyInteger('vehicle_experience')->change();
        });
    }
};
