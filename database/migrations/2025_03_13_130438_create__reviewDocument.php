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
        Schema::create('ReviewDocument', function (Blueprint $table) {
            $table->increments('reviewID');
            $table->unsignedInteger('requestID')->index();
            $table->string('reviewComment');
            $table->timestamp('reviewDate');
            $table->string('reviewStatus');
            $table->unsignedBigInteger('userID')->index();
            $table->foreign('requestID')->references('requestID')->on('RequestDocument');
            $table->foreign('userID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ReviewDocument');
    }
};