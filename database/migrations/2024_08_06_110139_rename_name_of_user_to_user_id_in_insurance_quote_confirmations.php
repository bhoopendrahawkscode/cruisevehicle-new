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
        Schema::table('insurance_quote_confirmations', function (Blueprint $table) {
            // Rename the column 'name_of_user' to 'user_id'
            $table->renameColumn('name_of_user', 'user_id');

            // Change the type of 'user_id' to integer if it was not already
            $table->integer('user_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_quote_confirmations', function (Blueprint $table) {
            // Revert the column name back to 'name_of_user'
            $table->renameColumn('user_id', 'name_of_user');

            // Change the type of 'name_of_user' back to its original type if needed
            $table->string('name_of_user')->change();
        });
    }
};
