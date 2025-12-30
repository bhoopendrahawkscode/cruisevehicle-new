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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->nullable()->comment('1=user, 2=admin, 3=sub-admin');
            $table->string('email', 225)->nullable();
            $table->string('password', 225)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('full_name', 225)->nullable();
            $table->string('username', 255)->nullable();
            $table->date('dob')->nullable();
            $table->string('facebook_id', 225)->nullable();
            $table->string('google_id', 225)->nullable();
            $table->string('apple_id', 225)->nullable();
            $table->string('remember_token', 225)->nullable();
            $table->text('auth_token')->nullable();
            $table->string('apikey', 225)->nullable();
            $table->string('latitude', 225)->nullable();
            $table->string('longitude', 225)->nullable();
            $table->integer('status')->default(1)->comment('1=>Active,0=>InActive,3 = deleted');
            $table->integer('country')->default(0);
            $table->tinyInteger('notification_status')->nullable()->default(1);
            $table->integer('is_deleted')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->string('device_type', 50)->nullable();
            $table->text('device_token')->nullable();
            $table->string('country_code', 20)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->tinyInteger('mobile_verified')->nullable()->default(0);
            $table->integer('reset_password_otp')->default(0);
            $table->text('app_version')->nullable();
            $table->text('os_version')->nullable();
            $table->date('subscription_general')->nullable();
            $table->date('subscription_meditation')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('timezone', 50)->nullable()->default('UTC');
            $table->integer('longest_streak')->default(0);
            $table->date('streak_start_date')->nullable();
            $table->date('last_streak_login')->nullable();
            $table->integer('meditation_seconds')->default(0);
            $table->integer('prayer_seconds')->default(0);
            $table->string('referral_code', 50)->nullable();
            $table->integer('referral_count')->default(0);
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
