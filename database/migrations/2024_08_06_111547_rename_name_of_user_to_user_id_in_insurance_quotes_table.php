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
            // Rename the column `name_of_user` to `user_id`
            $table->renameColumn('name_of_user', 'user_id');
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
            // Rename the column `user_id` back to `name_of_user`
            $table->renameColumn('user_id', 'name_of_user');
        });
    }
};
