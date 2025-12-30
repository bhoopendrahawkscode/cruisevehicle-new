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
        Schema::create('blog_attachments', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('blog_id')->nullable();
            $table->integer('type')->default(1)->comment('1=s3,2=other');
            $table->string('url', 500)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_attachments');
    }
};
