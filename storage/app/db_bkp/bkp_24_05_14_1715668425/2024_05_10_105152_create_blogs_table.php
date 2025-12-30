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
        Schema::create('blogs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->integer('community_id')->default(0);
            $table->tinyText('name')->nullable();
            $table->longText('content')->nullable();
            $table->string('asking_help', 1000)->nullable();
            $table->string('offer_help', 1000)->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('video', 1000)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamp('last_reported_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
