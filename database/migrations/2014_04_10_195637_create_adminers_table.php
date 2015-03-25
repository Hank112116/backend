<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminers', function ($table) {
            $table->increments('id');
            $table->string('name', 30);
            
            $table->string('email', 100);
            $table->unique('email');

            $table->string('password', 100);

            $table->string('remember_token', 100);
            $table->integer('role_id')->unsigned();
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
        Schema::drop('adminers');
    }
}
