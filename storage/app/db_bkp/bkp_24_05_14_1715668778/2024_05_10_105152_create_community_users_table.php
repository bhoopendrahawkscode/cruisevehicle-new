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
        Schema::create('community_users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('community_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('status')->default(1)->comment('0 = bookmark
1 = pending , 2 = accepted');
            $table->string('question', 1000)->nullable();
            $table->text('answer')->nullable();
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
        Schema::dropIfExists('community_users');
    }
};
