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
        Schema::create('emailtemplate_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emailtemplate_id');
            $table->integer('language_id');
            $table->string('name', 255);
            $table->string('subject', 255)->nullable();
            $table->longText('email_body')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emailtemplate_translations');
    }
};
