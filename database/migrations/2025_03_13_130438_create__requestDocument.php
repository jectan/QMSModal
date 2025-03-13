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
        Schema::create('RequestDocument', function (Blueprint $table) {
            $table->increments('requestID');
            $table->unsignedInteger('requestTypeID')->index();
            $table->unsignedInteger('docTypeID')->index();
            $table->string('docRefCode');
            $table->integer('currentRevNo');
            $table->string('docTitle');
            $table->string('requestReason');
            $table->unsignedBigInteger('userID')->index();
            $table->string('requestFile');
            $table->date('requestDate');
            $table->string('requestStatus');
            $table->foreign('requestTypeID')->references('requestTypeID')->on('RequestType');
            $table->foreign('docTypeID')->references('docTypeID')->on('DocType');
            $table->foreign('userID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RequestDocument');
    }
};