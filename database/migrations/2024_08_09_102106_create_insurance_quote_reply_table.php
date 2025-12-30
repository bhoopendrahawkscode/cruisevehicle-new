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
        Schema::create('insurance_quote_reply', function (Blueprint $table) {
            $table->id();
            $table->string('request_reference_number')->nullable();
            $table->unsignedBigInteger('user_id')->default(null);
            $table->decimal('vehicle_value_to_be_insured', 10, 2)->nullable();
            $table->decimal('premium_proposed', 10, 2)->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('insurance_quote_reply');
    }
};
