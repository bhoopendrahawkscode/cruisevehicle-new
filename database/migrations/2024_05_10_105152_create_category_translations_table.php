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
        Schema::create('category_translations', function (Blueprint $category_translations) {
            $category_translations->increments('id');
            $category_translations->integer('category_id');
            $category_translations->integer('language_id');
            $category_translations->string('name', 255);
            $category_translations->string('slug', 500)->nullable();
            $category_translations->timestamps();
            $category_translations->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_translations');
    }
};
