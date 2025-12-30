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
         //
         Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('websiteTitle');
            $table->string('logo');
            $table->string('favicon');
            $table->string('companyEmail');
            $table->string('companyPhone');
            $table->text('companyAddress');
            $table->string('dateFormat');
            $table->text('googleAnalytics');
            $table->text('thirdPartyJs');
            $table->text('customCss');
            $table->string('primaryColor');
            $table->string('secondaryColor');
            $table->text('header');
            $table->text('footer');
            $table->decimal('version');
            $table->int('pageLimit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('settings');
    }
};
