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
        Schema::create('blog_users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('blog_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->tinyInteger('bookmark')->nullable()->default(0);
            $table->tinyInteger('archive')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_users');
    }
};
