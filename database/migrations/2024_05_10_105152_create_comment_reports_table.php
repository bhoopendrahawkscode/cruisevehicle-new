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
        Schema::create('comment_reports', function (Blueprint $comment_reports) {
            $comment_reports->integer('id', true);
            $comment_reports->bigInteger('user_id')->default(0)->comment('person who reported ');
            $comment_reports->bigInteger('comment_id')->default(0);
            $comment_reports->string('type', 50)->nullable();
            $comment_reports->longText('message')->nullable();
            $comment_reports->timestamp('created_at')->useCurrent();
            $comment_reports->timestamp('updated_at')->useCurrentOnUpdate()->default('0000-00-00 00:00:00');
            $comment_reports->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
