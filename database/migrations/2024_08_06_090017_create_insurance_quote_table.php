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
        Schema::create('insurance_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('request_reference_number')->nullable();
            $table->string('name_of_user');
            $table->decimal('vehicle_value_to_be_insured', 15, 2);
            $table->decimal('premium_proposed', 15, 2);
            $table->text('comment')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('insurance_quotes');
    }
};
