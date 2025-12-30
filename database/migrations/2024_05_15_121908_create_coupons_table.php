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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('offer_type',['flat', 'percentage']);
            $table->string('discount');
            $table->string('min_order_value');
            $table->string('discount_up_to');
            $table->string('code');
            $table->integer('maximum_uses');
            $table->integer('single_user_use_limit')->default(1);
            $table->string('image')->nullable();
            $table->longText('description');
            $table->boolean('status')->default(true);
            $table->datetime('start_date');
            $table->datetime('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
