<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('insurance_quotes', function (Blueprint $table) {
            $table->integer('insurance_cover_type')->nullable()->default(null)->after('premium_proposed');
            $table->string('insurance_period_requested')->nullable()->after('insurance_cover_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_quotes', function (Blueprint $table) {
            $table->dropColumn('insurance_cover_type');
            $table->dropColumn('insurance_period_requested');
        });
    }
};
