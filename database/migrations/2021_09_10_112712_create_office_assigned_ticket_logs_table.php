<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeAssignedTicketLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_assigned_ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assigned_office_ticket_id')->unsigned();
            $table->foreign('assigned_office_ticket_id')
                  ->references('id')
                  ->on('office_assigned_tickets')
                  ->onDelete('cascade');
            $table->string('status')->nullable();
            $table->dateTime('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by_id')->nullable();
            $table->foreign('status_updated_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('assigned_by_id')->nullable();
            $table->foreign('assigned_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('office_id')->unsigned();
            $table->foreign('office_id')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_assigned_ticket_logs');
    }
}
