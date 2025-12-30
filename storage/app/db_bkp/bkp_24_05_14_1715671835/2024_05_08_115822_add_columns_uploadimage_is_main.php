<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('uploadImageServer')->default('Local')->comment('Local','AWS')->after('pageLimit');
            $table->string('isMaintenanceMode')->default('Up')->comment('Up','Down');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumns(['uploadImageServer', 'isMaintenanceMode']);
        });
    }
};
