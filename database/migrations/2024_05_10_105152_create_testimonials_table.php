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
        Schema::create('testimonials', function (Blueprint $testimonials) {
            $testimonials->integer('id', true);
            $testimonials->string('image', 255)->nullable();
            $testimonials->integer('status')->default(1);
            $testimonials->dateTime('created_at')->useCurrent();
            $testimonials->dateTime('updated_at')->useCurrent();
            $testimonials->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
