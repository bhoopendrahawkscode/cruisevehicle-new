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
        Schema::create('insurance_renewal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->default(null);
            $table->boolean('vehicle_registered')->default(null);
            $table->boolean('vehicle_line')->default(null);
            $table->boolean('vehicle_disqualified')->default(null);
            $table->boolean('vehicle_experience')->default(null);
            $table->boolean('vehicle_accidents')->default(null);
            $table->boolean('vehicle_not_use')->default(null);
            $table->boolean('vehicle_drive_illness')->default(null);
            $table->boolean('vehicle_insurer')->default(null);
            $table->string('full_name')->default(null);
            $table->string('nic')->default(null);
            $table->unsignedBigInteger('car_model')->default(null);
            $table->year('year_of_manufacturer')->default(null);
            $table->string('vehicle_registration_mark')->default(null);
            $table->decimal('value', 15, 2)->default(null);
            $table->decimal('sum_to_be_insured', 15, 2)->default(null);
            $table->boolean('cover_type')->default(null);
            $table->boolean('period_of_insurance_cover')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_renewal');
    }
};
