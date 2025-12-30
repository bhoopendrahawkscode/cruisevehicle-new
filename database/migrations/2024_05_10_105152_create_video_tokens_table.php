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
        Schema::create('video_tokens', function (Blueprint $videoTable) {
            $videoTable->bigInteger('id', true);
            $videoTable->string('token', 50)->default('0');
            $videoTable->text('url')->nullable();
            $videoTable->integer('status')->default(1);
            $videoTable->dateTime('created_at')->useCurrent();
            $videoTable->dateTime('updated_at')->useCurrent();
            $videoTable->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_tokens');
    }
};
