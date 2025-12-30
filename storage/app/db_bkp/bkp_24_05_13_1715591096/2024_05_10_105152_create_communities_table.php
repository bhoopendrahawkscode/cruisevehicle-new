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
        Schema::create('communities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->tinyInteger('type')->default(1)->comment('1=public, 2=private, 3= hidden');
            $table->string('name', 500)->nullable();
            $table->string('question', 1000)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('code', 20)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('archive')->nullable()->default(0);
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
        Schema::dropIfExists('communities');
    }
};
