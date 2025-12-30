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
        Schema::create('community_reports', function (Blueprint $community_reports) {
            $community_reports->integer('id', true);
            $community_reports->bigInteger('user_id')->default(0)->comment('person who reported');
            $community_reports->bigInteger('community_id')->default(0);
            $community_reports->string('type', 50)->nullable();
            $community_reports->longText('message')->nullable();
            $community_reports->timestamp('created_at')->useCurrent();
            $community_reports->timestamp('updated_at')->useCurrentOnUpdate()->default('0000-00-00 00:00:00');
            $community_reports->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_reports');
    }
};
