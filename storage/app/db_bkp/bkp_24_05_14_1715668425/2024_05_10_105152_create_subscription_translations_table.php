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
        Schema::create('subscription_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_id');
            $table->integer('language_id');
            $table->string('name', 255);
            $table->string('validity', 255)->nullable();
            $table->string('price', 255)->nullable();
            $table->string('video_price', 255)->nullable();
            $table->string('songs_service', 255)->nullable();
            $table->string('video_service', 255)->nullable();
            $table->integer('subscription_type')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_translations');
    }
};
