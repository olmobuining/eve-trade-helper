<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('account_id');
            $table->integer('duration');
            $table->double('escrow');
            $table->boolean('is_buy_order');
            $table->boolean("is_corp");
            $table->dateTime("issued");
            $table->integer("location_id");
            $table->integer("min_volume");
            $table->integer("order_id");
            $table->double("price");
            $table->boolean("range");
            $table->integer("region_id");
            $table->string("state");
            $table->integer("type_id");
            $table->integer("volume_remain");
            $table->integer("volume_total");
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
        Schema::dropIfExists('orders');
    }
}
