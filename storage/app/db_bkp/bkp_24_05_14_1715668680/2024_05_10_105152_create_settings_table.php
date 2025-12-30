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
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('header')->nullable();
            $table->longText('footer')->nullable();
            $table->string('websiteTitle', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('companyEmail', 20);
            $table->string('companyPhone', 20);
            $table->text('companyAddress');
            $table->string('dateFormat', 255)->nullable();
            $table->text('googleAnalytics')->nullable();
            $table->text('thirdPartyJs')->nullable();
            $table->text('customCss')->nullable();
            $table->string('primaryColor', 50)->nullable();
            $table->string('secondaryColor', 50)->nullable();
            $table->decimal('version', 10)->nullable();
            $table->integer('pageLimit')->default(5);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
