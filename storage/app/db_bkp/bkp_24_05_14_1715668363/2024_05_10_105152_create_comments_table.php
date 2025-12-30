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
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->integer('blog_id')->default(0);
            $table->text('content')->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('video', 1000)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->string('attachment', 500)->nullable();
            $table->tinyInteger('attachment_type')->nullable()->default(0)->comment('1=s3,2=other');
            $table->string('users', 1000)->nullable();
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
        Schema::dropIfExists('comments');
    }
};
