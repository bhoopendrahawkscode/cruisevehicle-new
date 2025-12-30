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
        Schema::create('audio_translations', function (Blueprint $audio_translations) {
            $audio_translations->increments('id');
            $audio_translations->integer('audio_id');
            $audio_translations->integer('language_id');
            $audio_translations->string('name', 255);
            $audio_translations->string('artist', 255)->nullable();
            $audio_translations->timestamps();
            $audio_translations->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_translations');
    }
};
