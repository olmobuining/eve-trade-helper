<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('authorization_code')->unique();
            $table->string('email');
            $table->string('access_token');
            $table->dateTime('expire_datetime');
            $table->string('refresh_token');
            $table->string('character_id');
            $table->string('character_name');
            $table->timestamps();
            $table->index(['id', 'character_id']);
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
