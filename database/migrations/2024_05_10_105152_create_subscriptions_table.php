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
        Schema::create('subscriptions', function (Blueprint $subscriptions) {
            $subscriptions->integer('id', true);
            $subscriptions->integer('status')->default(1);
            $subscriptions->dateTime('created_at')->useCurrent();
            $subscriptions->dateTime('updated_at')->useCurrent();
            $subscriptions->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
