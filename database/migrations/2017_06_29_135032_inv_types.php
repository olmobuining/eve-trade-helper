<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('inv_types');
        Schema::create('inv_types', function (Blueprint $table) {
            $table->integer('typeID')->primary();
            $table->integer('groupID');
            $table->string('typeName');
            $table->text('description');
            $table->double('mass');
            $table->double('volume');
            $table->double('capacity');
            $table->integer('portionSize');
            $table->integer('raceID');
            $table->decimal('basePrice');
            $table->tinyInteger('published');
            $table->integer('marketGroupID');
            $table->integer('iconID');
            $table->integer('soundID');
            $table->integer('graphicID');
            $table->index(['groupID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_types');
    }
}
