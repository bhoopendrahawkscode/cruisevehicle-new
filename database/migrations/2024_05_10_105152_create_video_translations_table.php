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
        Schema::create('video_translations', function (Blueprint $video_translations) {
            $video_translations->increments('id');
            $video_translations->integer('video_id');
            $video_translations->integer('language_id');
            $video_translations->string('name', 255);
            $video_translations->string('artist', 255)->nullable();
            $video_translations->timestamps();
            $video_translations->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_translations');
    }
};
