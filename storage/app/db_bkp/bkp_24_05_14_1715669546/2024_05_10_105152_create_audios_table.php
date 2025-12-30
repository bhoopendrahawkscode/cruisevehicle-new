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
        Schema::create('audios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('audio')->nullable();
            $table->decimal('duration', 10)->default(0)->comment('in seconds');
            $table->string('image', 100)->default('1')->comment('1=free,2=paid');
            $table->tinyInteger('category')->nullable()->default(1)->comment(' 1=free,2=paid ');
            $table->integer('status')->default(1);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audios');
    }
};
