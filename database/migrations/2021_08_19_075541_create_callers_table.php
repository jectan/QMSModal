<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_no');
            $table->string('email')->nullable();
            // $table->boolean('is_anonymous')->default(false);

            $table->unsignedBigInteger('barangay_id')->unsigned();
            $table->foreign('barangay_id')
                  ->references('id')
                  ->on('barangays')
                  ->onDelete('cascade');  

            $table->unsignedBigInteger('created_by_id')->unsigned()->nullable();
            $table->foreign('created_by_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('updated_by_id')->unsigned()->nullable();
            $table->foreign('updated_by_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }  
     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('callers');
    }
}
