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
        Schema::create('reports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('from_id')->comment('user_id');
            $table->integer('to_id')->index('to_id')->comment('user_id');
            $table->text('msg')->nullable();
            $table->text('reason')->nullable();
            $table->string('category', 225)->nullable();
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['from_id', 'to_id'], 'from_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
