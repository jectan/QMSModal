<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id'); 

            $table->unsignedBigInteger('caller_id')->unsigned();
            $table->foreign('caller_id')
                  ->references('id')
                  ->on('callers')
                  ->onDelete('cascade');   

            $table->unsignedBigInteger('call_type_id')->unsigned();
            $table->foreign('call_type_id')
                  ->references('id')
                  ->on('caller_types')
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

            $table->string('ticket_no')->unique();
            $table->string('call_status');
            $table->longtext('call_details');
            $table->longtext('remarks')->nullable();
            $table->string('status');
            $table->string('ticket_category');
            $table->dateTime('call_datetime');
            $table->dateTime('status_update')->nullable();
            $table->longText('feedback')->nullable();
            $table->string('rating')->nullable();
            $table->dateTime('date_rated')->nullable();
            $table->dateTime('status_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
