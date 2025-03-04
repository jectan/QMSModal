<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('isNew')->default(1);
            $table->unsignedBigInteger('created_by_id')->unsigned()->nullable();
            $table->foreign('created_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');   
            $table->boolean('isActive')->default(1);
            $table->timestamp('blocked_at')->nullable();
            $table->unsignedBigInteger('blocked_by_id')->unsigned()->nullable();
            $table->foreign('blocked_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 
                $table->unsignedBigInteger('role_id')->unsigned();
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
