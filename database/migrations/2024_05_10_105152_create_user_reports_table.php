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
        Schema::create('user_reports', function (Blueprint $user_reports) {
            $user_reports->integer('id', true);
            $user_reports->bigInteger('user_id')->default(0)->comment('person who reported');
            $user_reports->bigInteger('owner_id')->default(0);
            $user_reports->string('type', 50)->nullable();
            $user_reports->longText('message')->nullable();
            $user_reports->timestamp('created_at')->useCurrent();
            $user_reports->timestamp('updated_at')->useCurrentOnUpdate()->default('0000-00-00 00:00:00');
            $user_reports->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
