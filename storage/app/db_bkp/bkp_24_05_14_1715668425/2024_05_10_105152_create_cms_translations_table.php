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
        Schema::create('cms_translations', function (Blueprint $table) {
            $table->comment('Table for cms page');
            $table->increments('id');
            $table->integer('cms_id')->nullable()->default(0);
            $table->integer('language_id')->nullable()->default(0);
            $table->string('title', 100)->nullable();
            $table->longText('body')->nullable();
            $table->text('meta_title')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->mediumText('meta_keywords')->nullable();
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_translations');
    }
};
