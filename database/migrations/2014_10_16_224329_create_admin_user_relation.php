<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adminers', function ($table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adminers', function ($table) {
            $table->dropColumn('user_id');
        });
    }
}
