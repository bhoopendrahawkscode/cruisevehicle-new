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
        Schema::create('gratitude_attachments', function (Blueprint $gratitude_attachments) {
            $gratitude_attachments->bigInteger('id', true);
            $gratitude_attachments->integer('gratitude_id')->nullable();
            $gratitude_attachments->integer('type')->default(1)->comment('1=s3,2=other');
            $gratitude_attachments->string('url', 500)->nullable();
            $gratitude_attachments->dateTime('created_at')->useCurrent();
            $gratitude_attachments->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratitude_attachments');
    }
};
